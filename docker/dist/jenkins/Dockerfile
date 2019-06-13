FROM tianon/true as skip_scheduler

FROM jenkins/jenkins:lts-alpine

USER root

RUN apk update && \
    apk add \
    docker \
    shadow

# Add user 'jenkins' to group 'ping' which has the same GID as 'docker' group (999)
# Required for access to docker.sock
RUN usermod -a -G ping jenkins
RUN usermod -a -G root jenkins

USER jenkins
