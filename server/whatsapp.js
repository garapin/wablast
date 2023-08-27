"use strict";
const _ = require("lodash");
const { Boom } = require("@hapi/boom");
//const { default: makeWASocket } = require("@adiwajshing/baileys");
const {default: makeWASocket} = require('@whiskeysockets/baileys');
const {
    fetchLatestBaileysVersion,

    useMultiFileAuthState,
    makeCacheableSignalKeyStore,
} = require("@whiskeysockets/baileys");
const { DisconnectReason } = require("@whiskeysockets/baileys");
const QRCode = require("qrcode");
// const logger = require('../../lib/pino')
//const lib = require("./lib");
const fs = require("fs");
let sock = [];
let qrcode = [];
let intervalStore = [];
const { setStatus } = require("./database/index");
const { IncomingMessage } = require("./controllers/incomingMessage");
const { formatReceipt } = require("./lib/helper");
const axios = require("axios");

/***********************************************************
 * FUNCTION
 **********************************************************/

const MAIN_LOGGER = require("./lib/pino");
const logger = MAIN_LOGGER.child({});


const connectToWhatsApp = async (token, io = null) => {
    if (typeof qrcode[token] !== "undefined") {
        if (io !== null) {
            io.emit("qrcode", {
                token,
                data: qrcode[token],
                message: "please scan with your Whatsapp Account",
            });
        }
        return {
            status: false,
            sock: sock[token],
            qrcode: qrcode[token],
            message: "Please scann qrcode",
        };
    }

    try {
        let number = sock[token].user.id.split(":");
        number = number[0] + "@s.whatsapp.net";
        const ppUrl = await getPpUrl(token, number);
        if (io !== null) {
            io.emit("connection-open", {
                token,
                user: sock[token].user,
                ppUrl,
            });
            console.log(sock[token].user);
        }

        return { status: true, message: "Already connected" };
    } catch (error) {
        if (io !== null) {
            io.emit("message", { token, message: `Connecting.. (1)` });
        }
    }

    const { version, isLatest } = await fetchLatestBaileysVersion();
    console.log(
        "You re using whatsapp gateway M Pedia v5.0.0 - Contact admin if any trouble : 082298859671"
    );
    console.log(`using WA v${version.join(".")}, isLatest: ${isLatest}`);

    // check or create credentials
    const { state, saveCreds } = await useMultiFileAuthState(
        `./credentials/${token}`
    );

    sock[token] = makeWASocket({
        version,
        // browser: ['Linux', 'Chrome', '103.0.5060.114'],
        browser: ["GarapinCloud", "Chrome", "103.0.5060.114"],
        logger,
        printQRInTerminal: true,
       
        auth: {
            creds: state.creds,
            keys: makeCacheableSignalKeyStore(state.keys, logger),
        },
        generateHighQualityLinkPreview : true,
    });

    // store?.bind(sock[token].ev)

    // sock[token].ev.on('messages.upsert', (m) => {
    //     autoReply(m, sock[token])
    // })

    sock[token].ev.process(async (events) => {
        if (events["connection.update"]) {
            const update = events["connection.update"];
            const { connection, lastDisconnect, qr } = update;
            if (connection === "close") {
                console.log("close");
                if (
                    (lastDisconnect?.error instanceof Boom)?.output
                        ?.statusCode !== DisconnectReason.loggedOut
                ) {
                    delete qrcode[token];
                    if (io != null)
                        io.emit("message", {
                            token: token,
                            message: "Connecting..",
                        });
                    if (
                        lastDisconnect.error?.output?.payload?.message ===
                        "QR refs attempts ended"
                    ) {
                        delete qrcode[token];
                        sock[token].ws.close();
                        if (io != null)
                            io.emit("message", {
                                token: token,
                                message:
                                    "Request QR ended. reload scan to request QR again",
                            });
                        return;
                    }
                    if (
                        lastDisconnect?.error.output.payload.message !=
                        "Stream Errored (conflict)"
                    ) {
                        connectToWhatsApp(token, io);
                    }
                } else {
                    setStatus(token, "Disconnect");
                    console.log("Connection closed. You are logged out.");
                    if (io !== null) {
                        io.emit("message", {
                            token,
                            message: "Connection closed. You are logged out.",
                        });
                    }
                    clearConnection(token);
                }
            }

            if (qr) {
                // SEND TO YOUR CLIENT SIDE
                QRCode.toDataURL(qr, function (err, url) {
                    if (err) {
                        console.log(err);
                    }
                    qrcode[token] = url;
                    if (io !== null) {
                        io.emit("qrcode", {
                            token,
                            data: url,
                            message: "Please scan with your Whatsapp Account",
                        });
                    }
                });
            }
            if (connection === "open") {
                setStatus(token, "Connected");
                let number = sock[token].user.id.split(":");
                number = number[0] + "@s.whatsapp.net";
                const ppUrl = await getPpUrl(token, number);
                if (io !== null) {
                    io.emit("connection-open", {
                        token,
                        user: sock[token].user,
                        ppUrl,
                    });
                }
                delete qrcode[token];
            }
        }

        if (events["messages.upsert"]) {
            
           
            const messages = events["messages.upsert"];
            IncomingMessage(messages, sock[token]);
        }

        if (events["creds.update"]) {
            const creds = events["creds.update"];
            saveCreds(creds);
        }
    });

    return {
        sock: sock[token],
        qrcode: qrcode[token],
    };
};
//
async function connectWaBeforeSend(token) {
    let status = undefined;
    let connect;
    connect = await connectToWhatsApp(token);

    await connect.sock.ev.on("connection.update", (con) => {
        const { connection, qr } = con;
        if (connection === "open") {
            status = true;
        }
        if (qr) {
            status = false;
        }
    });
    let counter = 0;
    while (typeof status === "undefined") {
        counter++;
        if (counter > 4) {
            break;
        }
        await new Promise((resolve) => setTimeout(resolve, 1000));
    }

    return status;
}
// text message
const sendText = async (token, number, text) => {
    try {
        const sendingTextMessage = await sock[token].sendMessage(
            formatReceipt(number),
            { text: text }
        ); // awaiting sending message
        console.log(sendingTextMessage);
        return sendingTextMessage;
    } catch (error) {
        console.log(error);
        return false;
    }
};
const sendMessage = async (token, number, msg) => {
    try {
        const sendingTextMessage = await sock[token].sendMessage(
            formatReceipt(number),
            JSON.parse(msg)
        ); 
        return sendingTextMessage;
    
    } catch (error) {
        console.log(error);
        return false;
    }
};

// media
async function sendMedia(
    token,
    destination,
    type,
    url,
    caption,
    ptt,
    filename
) {
    const number = formatReceipt(destination);
    try {
        if (type == "image") {
            var sendMsg = await sock[token].sendMessage(number, {
                image: url
                    ? { url }
                    : fs.readFileSync("src/public/temp/" + fileName),
                caption: caption ? caption : null,
            });
        } else if (type == "video") {
            var sendMsg = await sock[token].sendMessage(number, {
                video: url
                    ? { url }
                    : fs.readFileSync("src/public/temp/" + filename),
                caption: caption ? caption : null,
            });
        } else if (type == "audio") {
            var sendMsg = await sock[token].sendMessage(number, {
                audio: url
                    ? { url }
                    : fs.readFileSync("src/public/temp/" + filename),
                ptt: ptt == 0 ? false : true,
                mimetype: "audio/mpeg",
            });
        } else if (type == "pdf") {
            var sendMsg = await sock[token].sendMessage(
                number,
                { document: { url: url }, mimetype: "application/pdf", fileName : filename + ".pdf" },
                { url: url }
            );
        } else if (type == "xls") {
            var sendMsg = await sock[token].sendMessage(
                number,
                { document: { url: url }, mimetype: "application/excel" , fileName : filename + ".xls"},
                { url: url }
            );
        } else if (type == "xlsx") {
            var sendMsg = await sock[token].sendMessage(
                number,
                {
                    document: { url: url },
                    mimetype:
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                        fileName : filename + ".xlsx"
                },
                { url: url }
            );
        } else if (type == "doc") {
            var sendMsg = await sock[token].sendMessage(
                number,
                { document: { url: url }, mimetype: "application/msword"  , fileName : filename + ".doc"},
                { url: url }
            );
        } else if (type == "docx") {
            var sendMsg = await sock[token].sendMessage(
                number,
                {
                    document: { url: url },
                    mimetype:
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                        fileName : filename + ".docx"
                },
                { url: url }
            );
        } else if (type == "zip") {
            var sendMsg = await sock[token].sendMessage(
                number,
                { document: { url: url }, mimetype: "application/zip"  , fileName : filename + ".zip"},
                { url: url }
            );
        } else if (type == "mp3") {
            var sendMsg = await sock[token].sendMessage(
                number,
                { document: { url: url }, mimetype: "application/mp3" },
                { url: url }
            );
        } else {
            console.log("Please add your won role of mimetype");
            return {
                error: true,
                message: "Please add your won role of mimetype",
            };
        }
        // console.log(sendMsg)
        return sendMsg;
    } catch (error) {
        return false;
    }
}

// button message
async function sendButtonMessage(
    token,
    number,
    button,
    message,
    footer,
    image
) {
    /**
     * type is "url" or "local"
     * if you use local, you must upload into src/public/temp/[fileName]
     */
    let type = "url";
    try {
        const buttons = button.map((x, i) => {
            return {
                buttonId: i,
                buttonText: { displayText: x.displayText },
                type: 1,
            };
        });
        if (image) {
            var buttonMessage = {
                image:
                    type == "url"
                        ? { url: image }
                        : fs.readFileSync("src/public/temp/" + image),
                // jpegThumbnail: await lib.base64_encode(),
                caption: message,
                footer: footer,
                buttons: buttons,
                headerType: 4,
                viewOnce: true,
            };
        } else {
            var buttonMessage = {
                text: message,
                footer: footer,
                buttons: buttons,
                headerType: 1,
                viewOnce: true,
            };
        }
        const sendMsg = await sock[token].sendMessage(
            formatReceipt(number),
            buttonMessage
        );
        return sendMsg;
    } catch (error) {
        console.log(error);
        return false;
    }
}

async function sendTemplateMessage(token, number, button, text, footer, image) {
    try {
        // const templateButtons = [
        //     { index: 1, urlButton: { displayText: button[0].displayText, url: button[0].url } },
        //     { index: 2, callButton: { displayText: button[1].displayText, phoneNumber: button[1].phoneNumber } },
        //     { index: 3, quickReplyButton: { displayText: button[2].displayText, id: button[2].id } },
        // ]

        if (image) {
            var buttonMessage = {
                caption: text,
                footer: footer,
                viewOnce: true,
                templateButtons: button,
                image: { url: image },
                viewOnce: true,
            };
        } else {
            var buttonMessage = {
                text: text,
                footer: footer,
                viewOnce: true,

                templateButtons: button,
            };
        }

        const sendMsg = await sock[token].sendMessage(
            formatReceipt(number),
            buttonMessage
        );
        return sendMsg;
    } catch (error) {
        console.log(error);
        return false;
    }
}

// list message
async function sendListMessage(
    token,
    number,
    list,
    text,
    footer,
    title,
    buttonText
) {
    try {
        const listMessage = {
            text,
            footer,
            title,
            buttonText,
            sections: [list],
            viewOnce: true,
            
        };

        const sendMsg = await sock[token].sendMessage(
            formatReceipt(number),
            listMessage ,  {ephemeralExpiration: 604800}
        );
        return sendMsg;
    } catch (error) {
        console.log(error);
        return false;
    }
}


async function sendPollMessage(token,number,name,options,countable)
{
    try {
    
        const sendmsg =  await sock[token].sendMessage(formatReceipt(number),{
            poll : {
                name : name,
                values : options,
                selectableCount : countable
            }
        });
          
        return sendmsg;
    } catch (error) {
        console.log(error);
        return false;
    }
}
// feetch group

async function fetchGroups(token) {
    // check is exists token
    try {
        let getGroups = await sock[token].groupFetchAllParticipating();
        let groups = Object.entries(getGroups)
            .slice(0)
            .map((entry) => entry[1]);

        return groups;
    } catch (error) {
        return false;
    }
}

// if exist
async function isExist(token, number) {
    if (typeof sock[token] === "undefined") {
        const status = await connectWaBeforeSend(token);
        if (!status) {
            return false;
        }
    }
    try {
        if (number.includes("@g.us")) {
            return true;
        } else {
            const [result] = await sock[token].onWhatsApp(number);

            return result;
        }
    } catch (error) {
        return false;
    }
}

// ppUrl
async function getPpUrl(token, number, highrest) {
    let ppUrl;
    try {
        // if (highrest) {
        //     // for high res picture
        //     ppUrl = await sock[token].profilePictureUrl(number, 'image')
        // } else {
        // for low res picture
        ppUrl = await sock[token].profilePictureUrl(number);
        //  }

        return ppUrl;
    } catch (error) {
        return "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/1200px-WhatsApp.svg.png";
    }
}

// close connection
async function deleteCredentials(token, io = null) {
    if (io !== null) {
        io.emit("message", { token: token, message: "Logout Progres.." });
    }
    try {
        if (typeof sock[token] === "undefined") {
            const status = await connectWaBeforeSend(token);
            if (status) {
                sock[token].logout();
                delete sock[token];
            }
        } else {
            sock[token].logout();
            delete sock[token];
        }
        delete qrcode[token];
        clearInterval(intervalStore[token]);
        setStatus(token, "Disconnect");

        if (io != null) {
            io.emit("Unauthorized", token);
            io.emit("message", {
                token: token,
                message: "Connection closed. You are logged out.",
            });
        }
        if (fs.existsSync(`./credentials/${token}`)) {
            fs.rmSync(
                `./credentials/${token}`,
                { recursive: true, force: true },
                (err) => {
                    if (err) console.log(err);
                }
            );
            // fs.unlinkSync(`./sessions/session-${device}.json`)
        }

        // fs.rmdir(`credentials/${token}`, { recursive: true }, (err) => {
        //     if (err) {
        //         throw err;
        //     }
        //     console.log(`credentials/${token} is deleted`);
        // });

        return {
            status: true,
            message: "Deleting session and credential",
        };
    } catch (error) {
        console.log(error);
        return {
            status: true,
            message: "Nothing deleted",
        };
    }
}

async function getChromeLates() {
    const req = await axios.get(
        "https://versionhistory.googleapis.com/v1/chrome/platforms/linux/channels/stable/versions"
    );
    return req;
}

function clearConnection(token) {
    clearInterval(intervalStore[token]);

    delete sock[token];
    delete qrcode[token];
    setStatus(token, "Disconnect");
    if (fs.existsSync(`./credentials/${token}`)) {
        fs.rmSync(
            `./credentials/${token}`,
            { recursive: true, force: true },
            (err) => {
                if (err) console.log(err);
            }
        );
        console.log(`credentials/${token} is deleted`);
    }
    // fs.rmdir(`credentials/${token}`, { recursive: true }, (err) => {
    //     if (err) {
    //         throw err;
    //     }
    //     console.log(`credentials/${token} is deleted`);
    // });
}

async function initialize(req, res) {
    const { token } = req.body;
    if (token) {
        const fs = require("fs");
        const path = `./credentials/${token}`;
        if (fs.existsSync(path)) {
            sock[token] = undefined;
            const status = await connectWaBeforeSend(token);
            if (status) {
                return res
                    .status(200)
                    .json({ status: true, message: "Connection restored" });
            } else {
                return res
                    .status(200)
                    .json({ status: false, message: "Connection failed" });
            }
        }
        return res.send({
            status: false,
            message: `${token} Connection failed,please scan first`,
        });
    }
    return res.send({ status: false, message: "Wrong Parameterss" });
}

module.exports = {
    connectToWhatsApp,
    sendText,
    sendMedia,
    sendButtonMessage,
    sendTemplateMessage,
    sendListMessage,
    sendPollMessage,
    isExist,
    getPpUrl,
    fetchGroups,
    deleteCredentials,
    sendMessage,
    initialize,
    connectWaBeforeSend,
};
