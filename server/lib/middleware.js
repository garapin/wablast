const wa = require("../whatsapp");
const { formatReceipt } = require("./helper");

const checkDestination = async (req, res, next) => {
    const { token, number } = req.body;
    if (token && number) {
        const check = await wa.isExist(token, formatReceipt(number));
        if (!check) {
            return res.send({
                status: false,
                message:
                    "The destination Number not registered in WhatsApp or your sender not connected",
            });
        }
        next();
    } else {
        res.send({ status: false, message: "Check your parameter" });
    }
};

const checkConnectionBeforeBlast = async (req, res, next) => {
    const data = JSON.parse(req.body.data);
    const check = await wa.isExist(
        data.sender,
        formatReceipt(data.sender)
    );

    if (!check) {
        return res.send({
            status: false,
            message: "Check your whatsapp connection",
        });
    }

    next();
};

module.exports = { checkDestination, checkConnectionBeforeBlast };
