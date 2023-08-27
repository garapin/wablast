"use strict";

const wa = require("../whatsapp");

const createInstance = async (req, res) => {
  const { token } = req.body;
  if (token) {
    try {
      const connect = await wa.connectToWhatsApp(token, req.io);
      const status = connect?.status;
      const message = connect?.message;
      return res.send({
        status: status ?? "processing",
        qrcode: connect?.qrcode,
        message: message ? message : "Processing",
      });
    } catch (error) {
      console.log(error);
      return res.send({ status: false, error: error });
    }
  }
  res.status(403).end("Token needed");
};

const sendText = async (req, res) => {
  const { token, number, text } = req.body;
  if (token && number && text) {
    const sendingTextMessage = await wa.sendText(token, number, text);
    return handleResponSendMessage(sendingTextMessage, res);
  }
  res.send({ status: false, message: "Check your parameter" });
};

const sendMedia = async (req, res) => {
  const { token, number, type, url, caption, ptt, filename } = req.body;
  if (token && number && type && url) {
    const sendingMediaMessage = await wa.sendMedia(
      token,
      number,
      type,
      url,
      caption ?? "",
      ptt,
      filename
    );
    return handleResponSendMessage(sendingMediaMessage, res);
  }
  res.send({ status: false, message: "Check your parameter" });
};

const sendButtonMessage = async (req, res) => {
  const { token, number, button, message, footer, image } = req.body;
  const buttons = JSON.parse(button);
  if (token && number && button && message) {
    const sendButtonMessage = await wa.sendButtonMessage(
      token,
      number,
      buttons,
      message,
      footer,
      image
    );
    return handleResponSendMessage(sendButtonMessage, res);
  }
  res.send({ status: false, message: "Check your parameterr" });
};

const sendTemplateMessage = async (req, res) => {
  const { token, number, button, text, footer, image } = req.body;
  if (token && number && button && text && footer) {
    const sendTemplateMessage = await wa.sendTemplateMessage(
      token,
      number,
      JSON.parse(button),
      text,
      footer,
      image
    );
    return handleResponSendMessage(sendTemplateMessage, res);
  }
  res.send({ status: false, message: "Check your parameter" });
};

const sendListMessage = async (req, res) => {
  const { token, number, list, text, footer, title, buttonText } = req.body;
  if (token && number && list && text && title && buttonText) {
    const sendListMessage = await wa.sendListMessage(
      token,
      number,
      JSON.parse(list),
      text,
      footer ?? "",
      title,
      buttonText
    );
    return handleResponSendMessage(sendListMessage, res);
  }
  res.send({ status: false, message: "Check your parameterr" });
};

const sendPoll = async (req, res) => {
  const { token, number, name, options,countable } = req.body;

  if (token && number && name && options && countable) {
   
    const sendPollMessage = await wa.sendPollMessage(
      token,
      number,
      name,
      JSON.parse(options),
      countable
    );
    return handleResponSendMessage(sendPollMessage, res);
  }
  res.send({ status: false, message: "Check your parameterrs" });
};

const fetchGroups = async (req, res) => {
  const { token } = req.body;
  if (token) {
    const fetchGroups = await wa.fetchGroups(token);
    return handleResponSendMessage(fetchGroups, res);
  }
  res.send({ status: false, message: "Check your parameter" });
};

const deleteCredentials = async (req, res) => {
  const { token } = req.body;
  if (token) {
    const deleteCredentials = await wa.deleteCredentials(token);
    return handleResponSendMessage(deleteCredentials, res);
  }
  res.send({ status: false, message: "Check your parameter" });
};

// handle respon send message
const handleResponSendMessage = (result, res, msg = null) => {
  if (result) {
    return res.send({ status: true, data: result });
  }
  return res.send({
    status: false,
    message: "Check your whatsapp connection",
  });
};
// end handle respon send message

module.exports = {
  createInstance,
  sendText,
  sendMedia,
  sendButtonMessage,
  sendTemplateMessage,
  sendListMessage,
  deleteCredentials,
  fetchGroups,
  sendPoll,
};
