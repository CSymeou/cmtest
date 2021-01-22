## Laravel Test Application

This mini application was prepared as a test for Margera.

You can view the app from [http://laratest.blupath.co.uk](http://laratest.blupath.co.uk). 

The app exposes a very simple user interface: 

- On loading the application, a list of subscribers to the mailing list will load and be presented on the page. 
- Next to each subscriber is a delete button that allows the user to remove the specific subscriber from the mailing list. 
- At the top of the page is also a 'New Member' button that allows a user to add a user to the mailing list.

## Context

Development was treated as an exercise in problem solving (researching relevant information and tools available to deliver based on a vague brief), rather than as an execise in development. As such, the app itself developed in a 'quick and dirty' fashion, and some development best practices were not followed. See limitations section below for more.

## Limitations

### Functional limitations

The app is a very simple Single Page Application for adding / removing subscribers from a pre-existing Campaign Monitor mailing list. The app uses the API touchpoints for retreiving a list of subscribers from a mailing list, adding a subscriber and removing a subscriber. There is no functionality for:

- Registering / logging in to access the functionality
- Creating / Deleting mailing lists
- Editing the name of an existing subscriber
- Switching to a different mailing list 
- Switching between Campaign Monitor accounts to manage the mailing lists of different users.

The above could be delivered of course, but I judged they fell beyond the scope of the exercise.

### Development limitations

Again, given the scope of the exercise some development best practices weren't followed.

- There are minimal integration and unit tests.
- There is no functionality to handle possible bad responses from the API. The app essentially assumes correct responses
- The ID of the mailing list to use is hard coded into the app, and there is no fallback if this changes. If a user manually logs in to Campaign Monitor and deletes the mailing list from the Web UI, the app will stop working.

## Technology and process

The instruction below are aimed towards a developer. If you require more detailed instructions for a non-technical audience to follow, please contact me.

### Tech stack and resources

The selection of technologies was made purely out of convenience: I used technologies I was most familiar with to save time.

- The application was developed as a simple monolithic app using Laravel and Vue: (https://laravel.com/ and https://vuejs.org/).
- For the frontend I used vuetify.js: https://vuetifyjs.com/en/
- To access the CampaignMonitor api, I found an already available Laravel package for working with CM (https://packagist.org/packages/bashy/laravel-campaignmonitor). This builds on top of Campaign Monitors existing php Wrapper, and basically simplifies calling the api from Laravel applications. I used this to save time.

### Challenges

There were no particularly great challenges, other than the time limit for delivering the app. A few issues that came up included:

- The documentation for the Laravel CampaignMonitor package is not that great, so I had to look into the code of the package itself to understand exactly how api calls need to be made.
- It appears that the internal calls to the CampaignMonitor database are asynchronous, so responses from the Campaign Monitor API are sent out before changes to the database are persisted. In simple words, the api may tell you that a subscriber was added or deleted from the mailing before this is actually persisted in the database. This can create an issue because when trying to refresh the list of users after a successful call to add / remove a user, the list retreived from the api may not yet have been updated. I therefore had to update the list of users programmatically after add / remove subscriber calls, rather than recalling the data again from the api.

All other issues were relatively minor and easy to resolve.

## Deployment

### Deploying locally

To deploy locally you will need to setup a local php and Laravel development environment. This is beyond the scope of this documentation. Please review the Laravel documents at https://laravel.com/docs/8.x/installation. 

Following setup of your environment, download the application code in a new project folder, setup your .env file (see 'Accessing the application code' below), and install dependencies using:

- composer install
- npm install

Alternatively, if you are comfortable with Docker, you can use the Docker image christossymeou/cmtest.

### Accessing the application code

You can clone this repository on your own local dev environment to test the app out. You will need to set up an .env file to define your database connection. You can download my example .env from [here](https://www.dropbox.com/sh/qr1113xgfblm2uf/AABsV0M9JHjatbA82QT1yPJxa?dl=0). 

You can also get the application as a docker image by pulling the public image christossymeou/cmtest. You can find a docker-compose.yml file from [here](https://www.dropbox.com/sh/qr1113xgfblm2uf/AABsV0M9JHjatbA82QT1yPJxa?dl=0). 

### Deploying remotely

The following are instructions to deploy the app in a nginx server using Docker. You can adapt the following for other server configurations.

1) Fire up a new nginx server with whichever cloud services provider you are using (I personally use Digital Ocean). Use an image with Docker preinstalled if one is available.

2) SSH into the server

3) If Docker is not already installed, go ahead and install it. For Digital Ocean instances, you can follow the directions [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04). I won't copy and paste them to save space.

4) Navigate to the server documents root. For ngingx:
    <pre>
    cd /var/www
    </pre>

5) Create a new folder for the application.
    <pre>
    mkdir laratest
    cd laratest
    </pre>

6) create a docker-compose.yml file to startup the app
    <pre>
    touch docker-compose.yml
    nano docker-compose.yml
    </pre>    

7) Copy the following in the docker-compose.yml file, save and close. 
<strong>Important note:</strong> In the ports configuration for the 'web' service, I am mapping port 8081 of the host, to port 8080 of the docker container. If you are using port 8081 in this server instance, select a different port. Also in the APP_URL env variable, make sure you define whichever URL you will want to access the app from. 
<pre>
version: '2.2'
services:
  web:
    image: christossymeou/cmtest
    container_name: cmtest
    restart: always
    ports:
      - 8081:8080
    volumes:
      - ./.env:/var/www/.env
    tty: true
</pre>

8) Once you've saved the file and are back in the command line, it's time to bring the service up. 
<pre>
docker-compose up -d
</pre>

9) The docker setup is now ready. As data is stored against a Campaign Monitor account there is no need to setup a database. Now we need to setup nginx to point to our application. We'll do this by setting up a reverse proxy to direct traffic to our application to port 8081, and thus to our application. First we need to go to define a relevant nginx configuration.

<pre>
    cd /etc/nginx/sites-available/
    touch laratest.conf
    nano laratest.conf
</pre>

10) Copy the following in the laratest.conf file. <strong>Important note:</strong>In the server_name line, change to the domain that you would like to access the app from. You'll need to use a domain name that you have access to the DNS settings for. For example, I'm using laratest.blupath.co.uk. Also, in the proxy_pass line, if you've defined a host port other than 8081 to the app container, you will need to use that one.

All we are saying here is, any traffic that comes in for this specific subdomain, map to port 8081 (or whichever port you've selected). And since in the docker-compose configuration we've mapped that port to the container port, any request to this domain will reach our dockerised application.

<pre>
server {

        listen 80;
        listen [::]:80;

        server_name laratest.blupath.co.uk;
        location / {
                proxy_pass http://0.0.0.0:8081;
                proxy_set_header Accept-Encoding "";
                proxy_set_header Host $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto $scheme;
        }
}
</pre>

11) Next create a symlink to this configuration from sites-available to sites-enabled

<pre>
    sudo ln -s /etc/nginx/sites-available/laratest.conf /etc/nginx/sites-enabled/laratest.conf
</pre>

12) Finally, restart the nginx server to make use of the new configurations

<pre>
    sudo service nginx restart
</pre>

13) The deployment is setup. The final step is to update your DNS settings so that the subdomain you've set up for the app points to the server. Disconnect from the server instance. Make a note of the server IP address. Then in the DNS for your domain, create a new A record to point the chosen subdomain to the IP of the server. In my case, I'm pointing laratest.blupath.co.uk to the IP of my DigitalOcean instance.

14) Navigate to the defined domain in your web browser. App should be and running. 
