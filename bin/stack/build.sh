#!/bin/bash
CWD=$(pwd)
PROJECT_DIR=$(dirname $0)/..
BUILD_DIR=$PROJECT_DIR/$(date +"%s")
make update
cd $BUILD_DIR
tar -zcvf dist/output.tar.gz ./
cd $CWD
