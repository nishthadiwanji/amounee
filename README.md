## Author
Ankit Gupta
Date: Nov 10, 2020

## Version

| Version | Information |
|  -----  |    -----    |
|  1.0.0  |  Basic Sentinel Setup, HashID, Activity Log, Export to Excel, Some Basic Needed Modules (Team Members)|

## The Repository
The repository includes basic setup of metronic theme with authentication, analysis reports, basic visitor management and invitation to guests functionality.

## Installation Steps
1. Clone the project.
2. Change directory to the project.
3. Create .env file if not exist.
4. Copy env.example contents to your .env file.
5. Create blank database in phpmyadmin as per .env DB_DATABASE.
6. Generate app key using 'php artisan generate:key' command.
7. Install composer using 'composer install' command.
8. Install node modules using 'npm install' command.
9. Run migration using 'php artisan migrate' command.
10. Run default seeders 'php artisan db:seed' command.
11. Run 'npm run development' command for compiling assets. 
12. This project must be needed to AWS credential for store any type of files, below the list of AWS credentials:
	- AWS_ACCESS_KEY_ID
	- AWS_SECRET_ACCESS_KEY
	- AWS_DEFAULT_REGION
	- AWS_BUCKET
without this credential system can not work properly.

Make sure the JWT Auth Token manager is "tymon/jwt-auth": "1.0.0-rc.5.1"

## Update Log

## License
Copyright (c) Amounee.co All rights reserved.
