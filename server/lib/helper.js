const {
    default: makeWASocket,
    downloadContentFromMessage,
} = require("@whiskeysockets/baileys")

function formatReceipt(receipt) {
    try {
        if (receipt.endsWith("@g.us")) {
            return receipt;
        }
        let formatted = receipt.replace(/\D/g, "");

        if (formatted.startsWith("0")) {
            formatted = "62" + formatted.substr(1);
        }

        if (!formatted.endsWith("@c.us")) {
            formatted += "@c.us";
        }

        return formatted;
    } catch (error) {
        return receipt;
    }

    // }
}
async function asyncForEach(array, callback) {
    for (let index = 0; index < array.length; index++) {
        await callback(array[index], index, array);
    }
}

async function removeForbiddenCharacters(input) {
    // remove forbidden characters and allow Arabic letters
    return input.replace(/[^a-zA-Z0-9 #\/:\.\@!\-\u0600-\u06FF]/g, "");
}


async function parseIncomingMessage(msg) {
    const type = Object.keys(msg.message || {})[0];
    const body =
        type === "conversation" && msg.message.conversation
            ? msg.message.conversation
            : type == "imageMessage" && msg.message.imageMessage.caption
            ? msg.message.imageMessage.caption
            : type == "videoMessage" && msg.message.videoMessage.caption
            ? msg.message.videoMessage.caption
            : type == "extendedTextMessage" &&
              msg.message.extendedTextMessage.text
            ? msg.message.extendedTextMessage.text
            : type == "messageContextInfo" &&
              msg.message.listResponseMessage?.title
            ? msg.message.listResponseMessage.title
            : type == "messageContextInfo"
            ? msg.message.buttonsResponseMessage.selectedDisplayText
            : "";
    const d = body.toLowerCase();
    const command = await removeForbiddenCharacters(d);
    const senderName = msg?.pushName || "";
    const from = msg.key.remoteJid.split("@")[0];
    let bufferImage;
    //  const urlImage = (type == 'imageMessage') && msg.message.imageMessage.caption ? msg.message.imageMessage.caption : null;
    if (type === "imageMessage") {
        const stream = await downloadContentFromMessage(
            msg.message.imageMessage,
            "image"
        );
        let buffer = Buffer.from([]);
        for await (const chunk of stream) {
            buffer = Buffer.concat([buffer, chunk]);
        }
        bufferImage = buffer.toString("base64");
    } else {
        urlImage = null;
    }

    return {  command, bufferImage, from };
}

module.exports = {
    formatReceipt,
    asyncForEach,
    removeForbiddenCharacters,
    parseIncomingMessage,
};
