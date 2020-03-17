# Laravel SaaS Boilerplate

Code boilerplate to start Laravel project with plans and user subscriptions.

![Laravel SaaS Boilerplate](https://quickadminpanel.com/blog/wp-content/uploads/2020/03/Screen-Shot-2020-03-02-at-3.47.55-PM.png)

- - - - -

## Features included

- __Users__ and __Subscription Plans__ (based on Roles)
- __Features__: Roles/permissions system to assign Features to Plans, with example of __Tasks__ and __Projects__ Features. 
- __Multi-tenancy__: every user sees their own records
- __Cashier__: Subscription system based on Laravel Cashier: subscribe to the plan, change plan, cancel plan, add/change payment method
- __Dashboard__: revenue report for administrator 


- - - - -

## Quick Start Video Guides

1. [Features: Quick Overview (2:34)](https://www.youtube.com/watch?v=reDdsxe4hLw)
2. [Installation Process (1:40)](https://www.youtube.com/watch?v=OgbmiNL3DfA)
3. [Roles/Permissions and Plans/Features System (4:03)](https://www.youtube.com/watch?v=BZ5FCZKkQx0)


- - - - -

## Installation

- Clone the repository with `git clone`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed` (it has some seeded data for your testing)
- If you want to have seeded 50 testing users, run `php artisan db:seed --class=DummySubscriptionsSeeder`
- That's it: launch the main URL and login with default credentials `admin@admin.com` - `password`
- You can click Register or go to `/register` URL and create a new user which would have Free Plan role by default


- - - - -

## Plans included by default

In the seeds, we have these plans/features:

- __Free Plan__: default for new registered users, can manage projects but only up to 5 projects. Cannot see/manage tasks.
- __Bronze Plan ($9.99/month)__: can manage unlimited projects, Cannot see/manage tasks.
- __Silver Plan ($19.99/month)__: can manage unlimited projects. Can see/manage tasks but only up to 5 tasks.
- __Gold Plan ($29.99/month)__: can manage unlimited projects and tasks.


- - - - -

## In progress / on the roadmap

- __Stripe webhooks__: email notification for customers/admins
- __Invoices__: generating/sending invoices and saving billing details
- __Yearly plans__: customizing plans system to have two sub-plans for each plan
- __Team plans__: invitation system with billing for the whole team
- __Discounts and coupons__: generating coupons and checking them at checkout
- __Taxes and VAT__: calculating taxes and using VAT API to calculate tax rate
- ... anything you want to add? [Create an issue](https://github.com/LaravelDaily/Laravel-SaaS-Boilerplate-Demo/issues)
