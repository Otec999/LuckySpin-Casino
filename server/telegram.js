const TelegramBot = require('node-telegram-bot-api');
const mysql = require('mysql');
const nodeCron = require("node-cron");
const request = require('requestify');

const bot = new TelegramBot("", {
    polling: {
        interval: 300,
        autoStart: true,
        params: {
            timeout: 10
        }
    }
})
const client = mysql.createPool({
    connectionLimit: 50,
    host: "localhost",
    user: "replace_user",
    database: "replace_db",
    password: "replace_password"
});

bot.on('message', async msg => {

    let chat_id = msg.chat.id,
        text = msg.text ? msg.text : '',
        settings = await db('SELECT * FROM settings ORDER BY id DESC');

    if(!text) return bot.sendMessage(chat_id, 'Сообщение не должно содержать картинки / смайлики / стикеры');

    if(text.toLowerCase() === '/start') {
            parse_mode: "HTML"
        });
    }

    else if(text.toLowerCase().startsWith('/bind')) {
        let id = text.split("/bind ")[1] ? text.split("/bind ")[1]  : 'undefined';
        id = id.replace(/[^a-z0-9\s]/gi);
        let user = await db('SELECT * FROM users WHERE id = ?', [id]);
        let check = await db('SELECT * FROM users WHERE tg_id = ?', [chat_id]);
        let subs = await bot.getChatMember('@demosoyou', chat_id).catch((err) => {});

        if (!subs || subs.status == 'left' || subs.status == undefined) {

                disable_web_page_preview: true
            });
        }
        if(user.length < 1) return bot.sendMessage(chat_id, 'Пользователь не найден', {
            parse_mode: "HTML"
        });
        if(check.length >= 1) return bot.sendMessage(chat_id, 'Этот Telegram аккаунт уже привязан');
        if(user[0].bonus_2 == 1) return bot.sendMessage(chat_id, 'Пользователю уже было начислено вознаграждение');
        console.log(user);

        await db('UPDATE users SET tg_id = ?, bonus_2 = 2 WHERE id = ?', [chat_id, id]);

        return bot.sendMessage(chat_id, `😎 Спасибо за подписку, ${user[0].name}!\n\nТеперь вы можете получить бонус на сайте.`);
    }

    return bot.sendMessage(chat_id, 'Команда не распознана', {
        reply_to_message_id: msg.message_id
    });
});

nodeCron.schedule('0 13 * * *', async () => {
    setTimeout(async () => {
        request.post('https://luckyspin.vip/createPromoTG').then(function(response) {
            const res = response.getBody();
💰 Промокод 10₽/250акт — ${res.promoSum}
        
⚡ Промокод на 15%/20акт — ${res.promoDep}
        
🚀 Актуальный домен — luckyspin.vip
        
📢 Сайт работает в штатном режиме, выводы в среднем до 2 часов.`, {
                reply_markup: JSON.stringify({
                inline_keyboard: [
                    [
                        { "text": "Активировать промокод", "url": "https://luckyspin.vip/" }
                    ]
                ]
                })
        })

        console.log(`[APP] Промокоды выданы`);
    }, 10 * 1000);
});

nodeCron.schedule('0 18 * * *', async () => {
    setTimeout(async () => {
        request.post('https://luckyspin.vip/createPromoTG').then(function(response) {
            const res = response.getBody();
            return bot.sendMessage("@demosoyou", `
💰 Промокод 10₽/250акт — ${res.promoSum}
        
        
🚀 Актуальный домен — luckyspin.vip
        
📢 Сайт работает в штатном режиме, выводы в среднем до 2 часов.`, {
                parse_mode: 'Markdown',
                reply_markup: JSON.stringify({
                inline_keyboard: [
                        { "text": "Активировать промокод", "url": "https://luckyspin.vip/" }
                    ]
                ]
                })
            })
        })

    }, 10 * 1000);
});

nodeCron.schedule('0 21 * * *', async () => {
    setTimeout(async () => {
        request.post('https://luckyspin.vip/createPromoTG').then(function(response) {
            const res = response.getBody();
            return bot.sendMessage("@demosoyou", `
💰 Промокод 10₽/250акт — ${res.promoSum}
        
⚡ Промокод на 15%/20акт — ${res.promoDep}
        
🚀 Актуальный домен — luckyspin.vip
📢 Сайт работает в штатном режиме, выводы в среднем до 2 часов.`, {
                parse_mode: 'Markdown',
                reply_markup: JSON.stringify({
                inline_keyboard: [
                    [
                        { "text": "Активировать промокод", "url": "https://luckyspin.vip/" }
                    ]
                })
            })
        })

        console.log(`[APP] Промокоды выданы`);
    }, 10 * 1000);
});
function makeIdentify(length) {
    var result = "";
    var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

async function sendCodes(type, name, amount, limit, wager, need_deposit) {
    request.post('https://luckyspin.vip/createPromoTG').then(function(response) {
        return bot.sendMessage("@demosoyou", `
        💰 Промокод 10₽/250акт —
        
        ⚡ Промокод на 15%/20акт —
        
        🚀 Актуальный домен — luckyspin.vip
        
        📢 Сайт работает в штатном режиме, выводы в среднем до 2 часов.`, {
            parse_mode: 'Markdown',
            reply_markup: JSON.stringify({
                [
                  { "text": "Активировать промокод", "url": "https://luckyspin.vip/" }
                ]
              ]
            })
        })
    return await ctx.telegram.sendMessage(config.telegram_channel_id, `
💰 Промокод 10₽/250акт —

⚡ Промокод на 15%/20акт —

🚀 Актуальный домен — luckyspin.vip

    parse_mode: 'Markdown',
    reply_markup: JSON.stringify({
      inline_keyboard: [
        [
          { "text": "Активировать промокод", "url": "https://luckyspin.vip/" }
        ]
      ]
    })
});
}

    return new Promise((resolve, reject) => {
        client.query(query, params, function (error, result) {
            if (error) {
                console.log(error);
                reject(error);
                return;
            }
        });
    });
}
