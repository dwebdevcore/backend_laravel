# Requirements

Docker

# Run

To start the project, just run:

```bash
docker-compose up
```

If you want to start containers in background(as a daemon), add the `-d` flag:

```bash
docker-compose up -d
```

If this is your first run, then after containers are up and ready, run the next command to setup/install the project dependencies:

```bash
docker-compose run --rm app ./build-deploy/first-run.sh
```

# Stop

You can stop containers by typing `Cmd + C` on Mac or `Ctrl + C` on Windows/Linux. 

If you started the project in background, then run:

```bash
docker-compose stop
```

# Cleanup

If you want to cleanup your Docker instances for a fresh start, run:

```bash
docker-compose down --rmi=local -v
```

This command will stop and delete the containers, local images and volumes.

# Updating external images

If you want to get latest versions of your external images, run:

```bash
docker-compose pull
```

# Queues

Once your containers are started, you can start the queue listeners when needed.

### Mailing Queue

```bash
docker-compose run --rm app php artisan queue:work sqs_mail
```

### SMS Queue

```bash
docker-compose run --rm app php artisan queue:work sqs_sms
```
### Mac Set-up

```Docker Mac

    1. Install the docker in your mac

    2. After installing docker in your mac clone the repo (app) into your machine 

    3. Configure .env file and change mysql db_host to 127.0.0.1 and also change in docker.yaml file. mysql Environment to `MYSQL_HOST=127.0.0.1` and `app: aliases: - 127.0.0.1`

    4. Inside your app run `composer commands` to update all packages

    5. after that run make sure you have the righ permissions for that
            go to app->bulid-deploy 
                run `sudo chmod  +x first_run.sh`
                run `sudo chmod  +x run.sh`
            next go to app->bulid-deploy->image
                run `sudo chmod  +x run.sh`

    6. Now run  `docker-compose build` and  inside your app

    7. Start Laravel server to access app

    If you use sequel pro for mysql use these credentials database: app, host: 127.0.0.1, username: root
    Now you are able to access the app `http://127.0.0.1:8000/dashboard:login` url

    ```
