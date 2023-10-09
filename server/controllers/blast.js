const { dbQuery } = require("../database");
const wa = require("../whatsapp");


let inProgress = [];

const sendBlastMessage = async (req, res) => {
    const data = JSON.parse(req.body.data);
    const dataBlast = data.data;
    const campaignId = data.campaign_id;

    const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

    if (inProgress[campaignId]) {
        console.log(
            `still any progress in campaign id ${campaignId}, request canceled. `
        );
        return res.send({ status: "in_progress" });
    }

    inProgress[campaignId] = true;
    console.log(`progress campaign ID : ${campaignId} started`);

    const send = async () => {
        for (let i in dataBlast) {
            const delay = data.delay;
            await sleep(delay * 1000);

            if (data.sender && dataBlast[i].receiver && dataBlast[i].message) {
                const checkBlast = await dbQuery(
                    `SELECT status FROM blasts WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}'`
                );
                if (checkBlast.length > 0) {
                    if (checkBlast[0].status === "pending") {
                        const sendingTextMessagePromise = wa.sendMessage(
                            data.sender,
                            dataBlast[i].receiver,
                            dataBlast[i].message
                        );

                        sendingTextMessagePromise.then(
                            async (sendingTextMessage) => {
                                if (sendingTextMessage) {
                                    await dbQuery(
                                        `UPDATE blasts SET status = 'success' WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}'`
                                    );
                                } else {
                                    await dbQuery(
                                        `UPDATE blasts SET status = 'failed' WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}'`
                                    );
                                }
                            }
                        );
                    } else {
                        console.log("no pending, not send!");
                    }
                }
            } else {
                console.log("wrong data, progress canceled!");
            }
        }

        return true;
    };

    send().then(() => {
        delete inProgress[campaignId];
    });

    res.send({ status: "in_progress" });
};




module.exports = {
    sendBlastMessage,
};
