# Zeus

UNDER DEVELOPMENT!

## Introduction

This is a Monitoring system made with PHP, using Zend Expressive micro-framework.

It's goal is to monitor servers and services in distributed systems, like in a micro service architeture.

## Usage

You can attach a [Kharon](https://github.com/mt-olympus/kharon) to the services bellow to collect the data and transport them 
to the Zeus server:

* [Athena](https://github.com/mt-olympus/athena) (Service Discovery): To monitor the servers and it's services
* [Cerberus](https://github.com/mt-olympus/cerberus) (Circuit Breaker): To monitor the services availability
* [Hermes](https://github.com/mt-olympus/hermes) (API communication): To monitor the services response times and trace requests through micro-services
* [LosLog](https://github.com/Lansoweb/LosLog) (Log): To monitor your application for errors

The data is displayed as dashboards and can be fetched via REST API.

You can create alarms based on the data collected from the services.
