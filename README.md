     ______   __         __     ______     __  __     ______
    /\  ___\ /\ \       /\ \   /\  ___\   /\ \/ /    /\  ___\
    \ \  __\ \ \ \____  \ \ \  \ \ \____  \ \  _"-.  \ \___  \
     \ \_\    \ \_____\  \ \_\  \ \_____\  \ \_\ \_\  \/\_____\
      \/_/     \/_____/   \/_/   \/_____/   \/_/\/_/   \/_____/

![demo](demo.gif)

A work in progress project built using Laravel and Tailwind CSS.

Flicks uses Laravel's queue and job batching capability to create data pipelines to hydrate the app's database with movie and tv film data from various sources. The schedule command is run automatically on a weekly basis using a cron. Additional data sources are continually being added.

Flicks currently has an internal API and a web layer built in PHP, Laravel and Tailwind CSS.

OAS API documentation for pre v1.0 can be found at `http://localhost/docs`. It is still a work in progress.

# Getting Started

There currently is no QA or Prod environments set up. In order to set up Flicks on your local environment it's recommended that you use the project's Docker configuration to get started quickly.

You'll need to install Docker Engine, Docker CLI and Docker Compose - it comes bundled with Docker Desktop and installation instructions for your OS can be found [here](https://docs.docker.com/get-docker/).

**NB.** If you are planning to install on WSL then you will need to have WSL2 installed. It's a straightforward process and instructions can be found [here](https://docs.docker.com/docker-for-windows/wsl/).

1. Once you have Docker installed, Git clone the repository to your machine

`git@github.com:daniel-norris/flicks.git`

2. The `.env` details you need to complete are commented in the `.env.example` file. Create a copy of this and enter your credentials.

**NB.** You will need to generate unique API keys for TMDb and IGBD databases if you are interested in testing the data import and batch capabilities of the application. Please be aware that the queue can be ~40min to complete. SMTP config for email capabilities need to be setup in your email provider and details included in the `.env` for email features to work.

3. There is a bash script that accompanies the project. Make it executable.

`chmod +x develop`

4. Spin up your Docker containers.

`./develop start`

5. Check that your containers are running. You should see 4 processes running for `php:7.3`, `phpmyadmin`, `mysql:5.7` and `redis:alpine`.

`docker ps`

6. Generate a key and migrate your database tables.

`./develop art key:generate`
`./develop art migrate`

7. You can now access the following from:

- app: `http://localhost:80`
- docs: `http://localhost/docs`
- phpmyadmin: `http://localhost:8080`

## Useful commands

If you want to run the queues run `./develop art schedule:run`. You can check logs at `storage/logs/laravel.log` or alternatively look at the jobs, imports and movies tables in phpmyadmin.

If you want to run a bash session directly into your database then run `docker ps`. Copy the container ID for mysql and run `docker exec --it <id> bash`. Then proceed to login to MySQL from the terminal.

