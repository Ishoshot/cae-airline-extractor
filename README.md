# CAE Airline Extractor

This README.md file provides instructions for setting up and running the application using Laravel Sail.

Postman collection named "CAE Airline Roster.postman_collection.json" can be found in the project root folder.

## Prerequisites

Before getting started, ensure you have the following prerequisites installed on your system:

-   Docker Desktop: [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

## Getting Started

Follow these steps to set up and run the application:

1. Clone the repository:

    ```bash
    git clone https://github.com/Ishoshot/cae-airline-extractor arline-extractor
    ```

2. Change into the project directory:

    ```bash
    cd arline-extractor
    ```

3. Copy the example environment file and update it with your configuration:

    ```bash
    cp .env.example .env
    ```

4. Build and start the Docker containers:

    To build and start the Docker containers using Laravel Sail, run the following command:

    ```bash
    ./vendor/bin/sail up -d
    ```

    This command will build the Docker images defined in the `docker-compose.yml` file and start the containers in detached mode.

5. Access the Laravel application:

    Once the Docker containers are up and running, you can access your Laravel application in your web browser at [http://localhost](http://localhost).

## Additional Commands

-   To stop the Docker containers, run:

    ```bash
    ./vendor/bin/sail down
    ```

-   To run Laravel Artisan commands within the Docker containers, prepend `sail` to the Artisan command. For example:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

-   To run PHPUnit tests within the Docker containers, use the following command:

    ```bash
    ./vendor/bin/sail test
    ```
