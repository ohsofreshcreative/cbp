FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# System packages + PHP 8.3 and the extensions required by Sage/Acorn and
# spatie/pdf-to-image (imagick + ghostscript for PDF rasterization).
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        php8.3-cli \
        php8.3-common \
        php8.3-mbstring \
        php8.3-xml \
        php8.3-curl \
        php8.3-zip \
        php8.3-gd \
        php8.3-intl \
        php8.3-bcmath \
        php8.3-imagick \
        ghostscript \
    && rm -rf /var/lib/apt/lists/*

# Composer (pinned major via official installer).
RUN curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

# Node.js 22 (NodeSource) + pnpm via corepack to match the repo's tooling.
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/* \
    && corepack enable \
    && corepack prepare pnpm@10.33.3 --activate

# The ubuntu:24.04 base image already ships a non-root `ubuntu` user (uid 1000).
USER ubuntu
