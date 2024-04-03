# Basic Telegram Bot With PHP

This repository contains a basic Telegram bot implemented in PHP. The bot demonstrates how to interact with the Telegram Bot API using PHP.
Also there is GeneralDb function that works fine with MySql Database you can execute Insert,Update,Delete and Select functions very simple and Easy.

## Features

- Basic command handling
- Sending and receiving messages and media from Telegram users
- Database Handler
- Handle Steps for any user
- Edit and Forward Messages
- Able to send Message to All Bot Users
- Seprated Admin and Users folder and commands

## Getting Started

Follow these steps to get started with the Telegram bot:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mhdi-khosravi/Basic-Telegram-Bot-With-PHP.git
2. **Install Dependencies:**
   ```bash
   composer install
   
3. **Create a Telegram Bot and Get API Token:**

Create a bot using BotFather.
Copy the API token provided by BotFather.

4. **Configure Bot Token:**

Update the TELEGRAM_BOT_TOKEN variable in the generalConfig.php file 
   ```bash
   define('TOKEN','TOKEN_HERE');
   ```

5. **Run the Bot:**
For running Bot best way is to use Telegram Websocket, in this Way you need a host and a domain that use SSL and point to main/bot.php file

***ATTENTION***

you can a code in main/bot.php file as below
```php
fastcgi_finish_request();
```
if you have a problem or bug in your codes when you save it in host, after sending a message to your bot you can't see any response because the bot is in a loop to proccess your request and send back a response but you have a bug.

so after fixing bug you need to wait some secounds and you can see a lot of response in a secound.

this line will fix this problem, even with a bug your bot never be in a loop and for sure with having responses in your code you can find the bug.

IF YOUR HOST IS LITESPEED YOU MUST USE THIS CODE:

```php
litespeed_finish_request();
```


## Contributing
Contributions are welcome! If you'd like to contribute to this project, please follow these steps:

Fork the repository.

Create a new branch for your feature: git checkout -b feature-name.

Make changes and commit them: git commit -m 'Add new feature'.

Push to the branch: git push origin feature-name.

Submit a pull request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.


