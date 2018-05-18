# N3 Uptime

N3 Uptime is a tool that regularly checks the health and availability of the servers.

## Set up initial environment

N3 Uptime can be included via [Composer](https://getcomposer.org):

```json
{
    "require": {
        "acquia/n3-uptime": "^1.0"
    },
    "repositories": [
        {
            "type": "git",
            "url": "git@github.com:acquia/n3-uptime.git"
        }
    ]
}
```

## Requirements
    
You will need the following software to develop, deploy, and maintain N3-Uptime:

    PHP 7.1 or higher
    Composer
    Curl
    AWS CLI
    Ruby 2.3 or higher


## Installation and usage

    make install

 
## Usage

You can now deploy your software to a new stack with:

    $ bundle exec moonshot create

By default, create launches the stack and deploys code. If you want to only create the stack and not deploy code, use:

    $ bundle exec moonshot create --no-deploy
    
If you make changes to your application and want to release a development build to your stack, run:

    $ bundle exec moonshot push
    
To build a "named build" for releasing through test and production environments, use:

    $ bundle exec moonshot build v0.1.0
    $ bundle exec moonshot deploy v0.1.0 -n <environment-name>
    
To see the outputs of the stack you just spun up:

    $ bundle exec moonshot status
    
Tear down your stack by running the following command:

    $ bundle exec moonshot delete
    
SSH into the first instance in your stack by running the following command:

    $ bundle exec moonshot ssh

## Additional information

* [N3 API issue backlog (for bug reports and feature requests)](https://backlog.acquia.com/projects/N3)
* [Working with Moonshot-powered services on N3](https://confluence.acquia.com/pages/viewpage.action?spaceKey=AN&title=Working+with+Moonshot-powered+services+on+N3)