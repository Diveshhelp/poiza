<p align="center"><a href="https://textbrew.ai/" target="_blank"><img src="https://cdn.prod.website-files.com/6422f4c111e30d1daa9dd518/6458f75dc19e765418cce46c_logo_color_bgtransparent_h_cropped.svg" width="400" alt="TextBrew Logo"></a></p>

## About TextBrew

The automated product description generator that simplifies your life.

## Running Queues in Laravel

To run queues in Laravel for TextBrew, follow these steps:

1. Make sure your queue connection is properly configured in your `.env` file:   QUEUE_CONNECTION=database

2. Run database migrations to create the jobs table: php artisan queue:table && php artisan migrate

3. To start the queue worker, run:    php artisan queue:work

For production, it's recommended to use a process monitor like Supervisor to keep the queue worker running.

4. To run failed jobs, use:    php artisan queue:retry all


For more information on Laravel queues, refer to the [official documentation](https://laravel.com/docs/queues).

## License

Copyright © 2024 Shoperating. All rights reserved.

This software and its associated documentation are proprietary and confidential.
Unauthorized copying, modification, distribution, or use of this software, in whole or in part,
is strictly prohibited without the express written permission of Shoperating.