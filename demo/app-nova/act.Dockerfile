FROM node:22-bookworm-slim

RUN apt-get update && apt-get -y install libsodium-dev
