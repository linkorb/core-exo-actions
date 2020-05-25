## Core Exo Actions

This project provides a set of common reusable actions for [Exo](https://github.com/linkorb/exo)

## Installation

    git clone git@github.com:linkorb/core-exo-actions.git
    cd core-exo-actions
    composer install # Installs the Exo CLI tool in vendor/bin + any PHP dependencies required by the actions in this repo
    cp .env.dist .env
    edit .env

## Usage

    # list actions
    vendor/bin/exo action

    # get action details
    vendor/bin/exo action random-number

    # run an action
    vendor/bin/exo run random-number -i min=10 -i max=100

    # run action as a full json input/output request
    vendor/bin/exo request < actions/random-number/example.request.json

## Creating new Exo actions

Create a new directory for your action in `actions/` (i.e. `actions/my-action/`) and add a file called `exo.action.yaml`.

As a starting point, you can copy one of the `exo.action.yaml` files from an existing action, and modify it to your requirements.

Then add a file that contains your action code. This can be a .php, .rb, .js, .sh, etc file, as long as it takes a JSON request on STDIN, and outputs a JSON response on STDOUT.

The request and response JSON should validate against the corresponding JSON schema: https://github.com/linkorb/exo/tree/master/schema



