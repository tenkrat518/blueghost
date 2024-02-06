#######################
Basic App for BlueGhost
#######################
/*

    Tested on PHP 8.3 

*/

1. Install curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
2. apt install symfony-cli
3. Connect to database via .env (example is shown there)
4. use command "php bin/console make:migration" in terminal/cmd 
5. use command "php bin/console doctrine:migrations:migrate" in terminal/cmd 
6. use command "symfony server:start" in terminal/cmd 
7. Open http://localhost:8000 in browser
 
8.Now you are ready to go.