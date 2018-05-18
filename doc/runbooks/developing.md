# N3 Uptime runbook: developing the application on a stack

## Pre-requisites

All commands should be run inside n3uptime's repository root and using the *csservicesdev* AWS account.

During development, you do not need to make any commits: your local changes will be picked up automatically.

### Runbooks

* [Working with Moonshot-powered services on N3](https://confluence.acquia.com/display/AN/Working+with+Moonshot-powered+services+on+N3)
* [Installation and initial setup](./installing.md)
* [Creating a stack](./creating.md)

## Code changes

Save your changes and deploy your code to the stack:

```sh
bundle exec moonshot push --environment=$STACKNAME
```

## CloudFormation template changes

Save your changes and update your stack:

```sh
bundle exec moonshot update --environment=$STACKNAME
```

Moonshot will calculate what has changed in the stack and ask you to confirm the update.
