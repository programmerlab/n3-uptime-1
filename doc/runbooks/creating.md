# N3 Uptime runbook: creating a stack

## Pre-requisites

All commands should be run inside n3uptime's repository root and using the *csservicesdev* AWS account.

### Runbooks

* [Working with Moonshot-powered services on N3](https://confluence.acquia.com/display/AN/Working+with+Moonshot-powered+services+on+N3)
* [Installation and initial setup](./installing.md)

### Initial variables

Create a stack name. For development, this should be named `dev-YOURNAME`. This stack name will be referred to as `$STACKNAME` for the rest of this runbook.

## Initialize the stack

Create an answers file for your stack:

```sh
cp moonshot/config/uptime-local.yml moonshot/config/answers-$STACKNAME.yml
```

Modify your answers file to suit your stack:

```yaml
AvailabilityZone1: us-east-1a                  # Leave default
AvailabilityZone2: us-east-1b                  # Leave default
Environment: dev                               # Leave default 
DomainHostedZone: dev.cloudservices.acquia.io  # Leave default
```

Create a new development stack, inputting your AWS account's MFA challenge when asked for `OTP code`:

```sh
bundle exec moonshot create --environment=$STACKNAME --answer-file=./moonshot/config/answers-$STACKNAME.yml --no-deploy
```

Creation of the stack can take up to 30 minutes. Once created, ensure the stack is running:

```sh
bundle exec moonshot status --environment=$STACKNAME
```
 
## Deploy N3Uptime to the stack

Deploy the Uptime source code and configuration to the new stack:

```sh
bundle exec moonshot push --environment=$STACKNAME
```

## Update the stack

Update the stack:

```sh
bundle exec moonshot update --environment=$STACKNAME --answer-file=./moonshot/config/answers-$STACKNAME.yml
```
