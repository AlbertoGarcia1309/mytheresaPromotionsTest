Capitole Mytheresa Alberto
This repository is part of the Capitole Mytheresa Alberto project, built as a technical test using Symfony, PHP, and Docker. This project showcases a clean and maintainable architecture, leveraging modern technologies and best practices.

Clone the repository:

````
bash
git clone https://github.com/your-username/capitole-mytheresa-alberto.git
````
Navigate to the project directory:

````
bash
cd capitole-mytheresa-alberto
````

Build and start the Docker containers:

```
bash
docker-compose up --build
````

Start the symfony server
```
docker exec -it symfony_app symfony server:start
```
It should not fail, but just in case: To create the fixtures in the db
```

docker exec -it symfony_app php bin/console make:migration
 docker exec -it symfony_app php bin/console doctrine:migrations:migrate
docker exec -it symfony_app php bin/console doctrine:fixtures:load
````

Then, to execute some of the tests that are asked:

```
docker exec -it symfony_app curl "http://localhost:8080/products?category=boots&priceLessThan=90000"
````

To execute php units tests:
```
 docker exec -it symfony_app ./vendor/bin/phpunit
```

Technologies:


This project utilizes the following technologies:

Symfony: Symfony is a PHP framework that provides a flexible and reusable set of components for building modern web applications. I chose Symfony for its strong architectural patterns, such as MVC, and its robust ecosystem of tools and bundles that make development faster and more efficient. Additionally, Symfony integrates well with various databases and other services, which makes it a great choice for scalable web applications.

PHP 7/8: PHP is a widely-used programming language that powers many modern websites and applications. The choice of PHP 7/8 allows for the use of the latest features and improvements, providing better performance and security than older versions. Symfony is optimized to work seamlessly with these versions of PHP.

MySQL: For the database layer, I used MySQL as the relational database management system. MySQL is highly reliable, fast, and widely supported, making it a natural choice for handling structured data in web applications. I opted for MySQL due to its robustness and compatibility with Docker, making it easy to manage in a containerized environment.

Docker: Docker is a platform for developing, shipping, and running applications in containers. I chose Docker for this project because it allows me to create a consistent and isolated environment for development. This helps avoid issues that may arise due to differences between local and production environments. Docker also makes it easy to manage dependencies like PHP, MySQL, and Symfony without worrying about system-level conflicts.

Git: Git is used for version control, allowing me to track changes, collaborate with others, and manage different versions of the codebase efficiently. Git is widely adopted in the development community, and its flexibility and speed make it a key tool for managing project history.
