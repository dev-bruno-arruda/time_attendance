# Ticto - Time and Attendance

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white) ![Quasar](https://img.shields.io/badge/Quasar-16B7FB?style=for-the-badge&logo=quasar&logoColor=black) ![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

## Ticto Time and Attendance backend

Usage of Make to streamline server commands

Install the libraries

```shell
make dci
```

Deploy the application
```shell
make up
```

Execute migrations and seeders

```shell
make mig
```

Execute tests

```shell
make test
```

Test results in ./coverage

### Users after migration

Employee User
```
User: employee@example.com
Password: password123
```

Admin User
```

User: admin@example.com
Password: password123
```

For new user, the default password is ticto123

### Ticto Time and Attendance Frontend

Follow the instructions to start the frontend

```shell
cd front_time_attendance
```

Install the dependencies

```bash
npm install
```

Start the app in development mode (hot-code reloading, error reporting, etc.)

```bash
quasar dev
```

## API Documentation

[Access this link](https://documenter.getpostman.com/view/7012858/2sAYBd6nwR) for API Documentation