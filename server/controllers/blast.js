const { dbQuery } = require("../database");
const wa = require("../whatsapp");


let inProgress = [];

const sendBlastMessage = async (req, res) => {
    const data = JSON.parse(req.body.data);
    const dataBlast = data.data;
    const campaignId = data.campaign_id;

    const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
	console.log ('jalankan sendblastmessage')
    if (inProgress[campaignId]) {
        console.log(
            `still any progress in campaign id ${campaignId}, request canceled. `
        );
        return res.send({ status: "in_progress" });
    }

    inProgress[campaignId] = true;
    

    const send = async () => {
        for (let i in dataBlast) {
			console.log(`progress blast ID : ${dataBlast[i].blast_id} started --- i : ${i}`);
            const delay = data.delay;
			const faktor_tambah = Math.floor(Math.random() * 21) + 10;
            await sleep((delay+faktor_tambah) * 1000);
			console.log (`done sleep blast ID :  ${dataBlast[i].blast_id} --- i : ${i} >> SENDING....`)
            if (data.sender && dataBlast[i].receiver && dataBlast[i].message) {
                const checkBlast = await dbQuery(
                    `SELECT status FROM blasts WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}' AND id = '${dataBlast[i].blast_id}'`
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
                                        `UPDATE blasts SET status = 'success' WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}' AND id = '${dataBlast[i].blast_id}'`
                                    );
                                } else {
                                    await dbQuery(
                                        `UPDATE blasts SET status = 'failed' WHERE receiver = '${dataBlast[i].receiver}' AND campaign_id = '${campaignId}' AND id = '${dataBlast[i].blast_id}'`
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
