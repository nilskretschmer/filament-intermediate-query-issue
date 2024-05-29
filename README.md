## 1. Clone the repository
    git clone <url>

## 2. Change into the new directory
    cd filament-relationship-query-issue

## 3. Install the dependencies
    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/opt -w /opt laravelsail/php83-composer:latest composer install --ignore-platform-reqs

## 4. Dump composer autoload
    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/opt -w /opt laravelsail/php83-composer:latest composer dump-autoload --ignore-platform-reqs

## 5. Copy and edit the *.env* file
    cp .env.example .env
    nano .env # or use editor of your choice

## 6. Start the server using Laravel Sail (Docker)
    vendor/bin/sail up -d

## 7. Generate a new key for the application
    vendor/bin/sail artisan key:generate

## 8. Migrate the database
    vendor/bin/sail artisan migrate

## 9. The Problem

#### 1. Attach a Group to a template

#### 2. Select group permissions and save

#### 3. Go to another template and attach the same group

#### 4. Give that group (the same) permissions

#### 5. See the intermediate table `template_group_permission` overwrite the "wrong" entries, because the template_id is ignored