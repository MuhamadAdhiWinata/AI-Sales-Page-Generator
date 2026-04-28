FROM node:20-slim

WORKDIR /app

# Create app directory and set ownership
RUN mkdir -p /app && chown -R node:node /app

USER node

# Copy package.json and package-lock.json
COPY --chown=node:node package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application
COPY --chown=node:node . .

# Expose the port Nuxt runs on
EXPOSE 3000

# Start the application
CMD ["npx", "nuxi", "dev", "--host", "0.0.0.0", "--port", "3000", "--allowed-hosts", "all"]
