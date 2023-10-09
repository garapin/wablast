const { dbQuery } = require("../database/index");
const { parseIncomingMessage, formatReceipt } = require("../lib/helper");
require("dotenv").config();

const axios = require("axios");
const {
    isExistsEqualCommand,
    isExistsContainCommand,
    getUrlWebhook,
} = require("../database/model");

const IncomingMessage = async (msg, sock) => {
    try {
        let quoted = false;
        if (!msg.messages) return;
        msg = msg.messages[0];
          const senderName = msg?.pushName || "";
        if (msg.key.fromMe === true) return;
        if (msg.key.remoteJid === "status@broadcast") return;
        const participant = msg.key.participant && formatReceipt(msg.key.participant)
        const { command, bufferImage, from } = await parseIncomingMessage(msg);
        let reply;
        let result;
        const numberWa = sock.user.id.split(":")[0];
        // check auto reply in database
        const checkEqual = await isExistsEqualCommand(command, numberWa);
        if (checkEqual.length > 0) {
            result = checkEqual;
        } else {
            result = await isExistsContainCommand(command, numberWa);
        }
        // end check autoreply in database
        if (result.length === 0) {
            console.log(msg);
            const url = await getUrlWebhook(numberWa);

            if (url == null) return;
            const r = await sendWebhook({
                command: command,
                bufferImage,
                from,
                url,
                participant
            });
            if (r === false) return;
            if (r === undefined) return;
            if( typeof r != 'object' ) return;
            quoted = r?.quoted ? true : false;
            reply = JSON.stringify(r);
        } else {
            replyorno =
                result[0].reply_when == "All"
                    ? true
                    : result[0].reply_when == "Group" &&
                      msg.key.remoteJid.includes("@g.us")
                    ? true
                    : result[0].reply_when == "Personal" &&
                      !msg.key.remoteJid.includes("@g.us")
                    ? true
                    : false;

            if (replyorno === false) return;
            quoted = result[0].is_quoted ? true : false;
            reply =  process.env.TYPE_SERVER === "hosting"
                    ? result[0].reply
                    : JSON.stringify(result[0].reply);
        }

        // replace if exists {name} with sender name in reply
        reply = reply.replace(/{name}/g, senderName);
        await sock
            .sendMessage(msg.key.remoteJid, JSON.parse(reply), {
                quoted: quoted ? msg : null,
            })
            .catch((e) => {
                console.log(e);
            });
        return true;
    } catch (e) {
        console.log(e);
    }
};

async function sendWebhook({ command, bufferImage, from, url,participant }) {
    try {
        const data = {
            message: command,
            bufferImage: bufferImage == undefined ? null : bufferImage,
            from,
            participant
        };
        const headers = { "Content-Type": "application/json; charset=utf-8" };
        const res = await axios.post(url, data, headers).catch(() => {
            return false;
        });
        return res.data;
    } catch (error) {
        console.log('error send webhook', error)
        return false;
    }
}

module.exports = { IncomingMessage };
