# TechBuy Laravel Azure Hosting Guide

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Overview of Azure Services](#overview-of-azure-services)
3. [Step 1: Setting up Azure Database for PostgreSQL](#step-1-setting-up-azure-database-for-postgresql)
4. [Step 2: Setting up Azure CosmosDB for MongoDB](#step-2-setting-up-azure-cosmosdb-for-mongodb)
5. [Step 3: Creating Azure App Service](#step-3-creating-azure-app-service)
6. [Step 4: Configuring Environment Variables](#step-4-configuring-environment-variables)
7. [Step 5: Deploying Your Application](#step-5-deploying-your-application)
8. [Step 6: SSL Certificate and Custom Domain](#step-6-ssl-certificate-and-custom-domain)
9. [Step 7: Monitoring and Logs](#step-7-monitoring-and-logs)
10. [Troubleshooting](#troubleshooting)
11. [Cost Optimization](#cost-optimization)

---

## Prerequisites

Before starting, make sure you have:

-   ✅ An active Azure account (which you already have)
-   ✅ Your TechBuy Laravel application ready locally
-   ✅ Git installed on your computer
-   ✅ Basic understanding of your application's dual database setup

---

## 🤔 Important: Local vs Production Differences

**You might be wondering:** "Locally I run both `npm run dev` and `php artisan serve` - how does this work on Azure?"

**Great question!** Here's the key difference:

### Local Development (what you do now):
```bash
npm run dev        # Vite dev server (hot-reloading, development mode)
php artisan serve  # Laravel development server
```
- Two separate servers running
- Assets are served in development mode with hot-reloading
- Perfect for development but not suitable for production

### Production on Azure (what this guide sets up):
```bash
npm run build      # Compiles assets into optimized static files
# Azure App Service automatically serves your Laravel app + compiled assets
```
- **One single server** (Azure App Service) serves everything
- Assets are pre-compiled, optimized, and served as static files
- Much faster and more efficient for production

**Bottom Line:** Yes, this guide handles everything! Azure App Service will serve both your Laravel backend AND your frontend assets from a single service. No need to worry about running multiple servers in production.

---

## Overview of Azure Services

Your TechBuy application uses a **dual database setup**, so we'll need these Azure services:

1. **Azure Database for PostgreSQL** - For users, orders, carts (primary database)
2. **Azure CosmosDB (MongoDB API)** - For products and categories
3. **Azure App Service** - To host your Laravel application
4. **Azure Storage Account** (optional) - For file uploads and static assets
5. **Azure Application Insights** (optional) - For monitoring

---

## Step 1: Setting up Azure Database for PostgreSQL

### 1.1 Create PostgreSQL Database

1. **Login to Azure Portal**

    - Go to [https://portal.azure.com](https://portal.azure.com)
    - Sign in with your Azure account

2. **Create PostgreSQL Server**

    - Click "Create a resource" (+ icon in top left)
    - Search for "Azure Database for PostgreSQL"
    - Select "Azure Database for PostgreSQL flexible server"
    - Click "Create"

3. **Configure Basic Settings**

    ```
    Resource Group: Create new → "techbuy-resources"
    Server name: techbuy-postgresql (must be globally unique)
    Region: Choose closest to your users (e.g., East US, West Europe)
    PostgreSQL version: 14 or 15 (recommended)
    Workload type: Development
    ```

4. **Configure Administrator Account**

    ```
    Admin username: techbuyadmin
    Admin password: Create a strong password (save this!)
    Confirm password: Re-enter your password
    ```

5. **Configure Networking**

    - Select "Public access (allowed IP addresses)"
    - Check "Allow public access from any Azure service within Azure"
    - Add your current IP address for management

6. **Review and Create**
    - Click "Review + create"
    - Wait for deployment (takes 5-10 minutes)

### 1.2 Configure PostgreSQL Database

1. **Connect to Server**

    - Once deployed, go to your PostgreSQL resource
    - Note down the "Server name" (will be like `techbuy-postgresql.postgres.database.azure.com`)

2. **Create Database**

    - Go to "Databases" in the left menu
    - Click "Add"
    - Database name: `techbuy_users`
    - Click "Save"

3. **Configure Firewall**
    - Go to "Networking" in the left menu
    - Add your current IP address if not already added
    - We'll add the App Service IP later

---

## Step 2: Setting up Azure CosmosDB for MongoDB

### 2.1 Create CosmosDB Account

1. **Create CosmosDB Resource**

    - In Azure Portal, click "Create a resource"
    - Search for "Azure Cosmos DB"
    - Click "Create"

2. **Select API**

    - Choose "Azure Cosmos DB for MongoDB"
    - Click "Create"

3. **Configure Basic Settings**

    ```
    Resource Group: techbuy-resources (select existing)
    Account name: techbuy-cosmosdb (must be globally unique)
    Location: Same as your PostgreSQL server
    Capacity mode: Provisioned throughput
    Apply Free Tier Discount: Yes (if available)
    ```

4. **Configure Networking**

    - Network connectivity: Public endpoint (all networks)
    - We'll secure this later

5. **Review and Create**
    - Click "Review + create"
    - Wait for deployment (takes 5-10 minutes)

### 2.2 Configure MongoDB Database

1. **Access Connection Details**

    - Go to your CosmosDB resource
    - Click "Connection strings" in left menu
    - Copy the "Primary Connection String"
    - Save this for later!

2. **Create Database and Collection**
    - Go to "Data Explorer"
    - Click "New Database"
    - Database id: `techbuy_products`
    - Click "OK"

---

## Step 3: Creating Azure App Service

### 3.1 Create App Service Plan

1. **Create App Service**

    - In Azure Portal, click "Create a resource"
    - Search for "App Service"
    - Click "Create"

2. **Configure Basic Settings**

    ```
    Resource Group: techbuy-resources (select existing)
    Name: techbuy-app (must be globally unique)
    Publish: Code
    Runtime stack: PHP 8.2
    Operating System: Linux
    Region: Same as your databases
    ```

3. **Configure App Service Plan**

    ```
    Linux Plan: Create new
    Name: techbuy-plan
    Pricing tier: B1 Basic (for production) or F1 Free (for testing)
    ```

4. **Review and Create**
    - Click "Review + create"
    - Wait for deployment

### 3.2 Configure App Service Settings

1. **Configure PHP Settings**

    - Go to your App Service resource
    - Click "Configuration" in left menu
    - Go to "General settings" tab

    ```
    Stack: PHP
    Major version: 8.2
    Minor version: 8.2
    Startup Command: Leave empty for now
    ```

2. **Enable Extensions**
    - Still in Configuration
    - We'll add required PHP extensions in environment variables

---

## Step 4: Configuring Environment Variables

### 4.1 Set Application Settings

In your App Service → Configuration → Application settings, add these variables:

**Basic Laravel Settings:**

```
APP_NAME=TechBuy
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate-this-later>
APP_URL=https://techbuy-app.azurewebsites.net
```

**PostgreSQL Settings:**

```
DB_CONNECTION=pgsql
DB_HOST=techbuy-postgresql.postgres.database.azure.com
DB_PORT=5432
DB_DATABASE=techbuy_users
DB_USERNAME=techbuyadmin
DB_PASSWORD=<your-postgresql-password>
```

**MongoDB Settings:**

```
MONGODB_DSN=<your-cosmosdb-connection-string>
```

**Session and Cache:**

```
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

**Mail Configuration (optional):**

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=<your-email>
MAIL_PASSWORD=<your-app-password>
MAIL_ENCRYPTION=tls
```

### 4.2 Configure PHP Extensions

Add these to Application Settings:

```
PHP_EXTENSIONS=pdo_pgsql,mongodb,zip,curl,gd,mbstring,xml
```

---

## Step 5: Deploying Your Application

### 5.1 Prepare Your Application

1. **Update Configuration Files**

    Update your `.env` file locally to match Azure settings:

    ```bash
    # Create a production .env file
    cp .env .env.production
    ```

2. **Optimize for Production**

    ```bash
    # Run these commands locally BEFORE deploying
    php artisan config:cache
    php artisan route:cache  
    php artisan view:cache
    composer install --optimize-autoloader --no-dev
    npm run build  # 🎯 This replaces your "npm run dev" for production!
    ```

    **What `npm run build` does:**
    - Compiles your TailwindCSS into optimized CSS
    - Bundles and minifies your JavaScript
    - Creates production-ready assets in `public/build/`
    - These files will be served directly by Azure (no need for separate Vite server!)

### 5.2 Deploy via Git

1. **Setup Deployment Center**

    - In your App Service, go to "Deployment Center"
    - Choose "GitHub" or "Local Git"
    - Follow the authentication steps

2. **If using Local Git:**

    ```bash
    # Get your Git URL from Deployment Center
    git remote add azure <your-git-url>

    # Deploy
    git add .
    git commit -m "Deploy to Azure"
    git push azure main
    ```

3. **If using GitHub:**
    - Connect your GitHub repository
    - Select your branch (main)
    - Azure will automatically deploy when you push changes

### 5.3 Post-Deployment Setup

1. **Generate Application Key**

    SSH into your App Service or use the Console:

    ```bash
    php artisan key:generate --show
    ```

    Copy this key and add it to Application Settings as `APP_KEY`

2. **Run Migrations**

    ```bash
    php artisan migrate --force
    ```

3. **Seed Database (if needed)**
    ```bash
    php artisan db:seed --force
    ```

---

## Step 6: SSL Certificate and Custom Domain

### 6.1 Default SSL

Your app automatically gets SSL with the default domain:
`https://techbuy-app.azurewebsites.net`

### 6.2 Custom Domain (Optional)

1. **Purchase Domain**

    - Buy a domain from any registrar
    - Or use Azure DNS

2. **Add Custom Domain**

    - In App Service, go to "Custom domains"
    - Click "Add custom domain"
    - Enter your domain name
    - Verify domain ownership

3. **Add SSL Certificate**
    - In "TLS/SSL settings"
    - Click "Private Key Certificates"
    - Upload your certificate or use App Service Managed Certificate (free)

---

## Step 7: Monitoring and Logs

### 7.1 Enable Application Insights

1. **Create Application Insights**

    - In Azure Portal, create "Application Insights"
    - Link it to your App Service

2. **Configure Logging**
    - In App Service → Monitoring → App Service logs
    - Enable "Application logging"
    - Set level to "Information"

### 7.2 View Logs

-   **Live Logs**: App Service → Monitoring → Log stream
-   **Application Logs**: App Service → Monitoring → App Service logs
-   **Metrics**: Monitor performance and errors

---

## Troubleshooting

### Common Issues and Solutions

1. **Database Connection Errors**

    ```
    Issue: Can't connect to PostgreSQL
    Solution:
    - Check firewall settings
    - Verify connection string
    - Ensure App Service IP is whitelisted
    ```

2. **MongoDB Connection Errors**

    ```
    Issue: Can't connect to CosmosDB
    Solution:
    - Verify connection string format
    - Check if MongoDB extension is installed
    - Verify network access rules
    ```

3. **Application Key Errors**

    ```
    Issue: "No application encryption key has been specified"
    Solution: Generate and set APP_KEY in Application Settings
    ```

4. **File Permission Errors**

    ```
    Issue: Storage folder not writable
    Solution: Laravel on Azure handles this automatically,
    but ensure your code doesn't try to write to read-only areas
    ```

5. **Composer Dependencies**
    ```
    Issue: Missing extensions or packages
    Solution: Add to PHP_EXTENSIONS in Application Settings
    ```

### Debug Steps

1. **Check Application Logs**

    - Go to App Service → Log stream
    - Look for specific error messages

2. **Test Database Connections**

    - Use SSH/Console to test connections

    ```bash
    php artisan tinker
    DB::connection('pgsql')->select('SELECT 1');
    DB::connection('mongodb')->collection('test')->count();
    ```

3. **Verify Environment Variables**
    ```bash
    php artisan config:show database
    ```

---

## Cost Optimization

### Estimated Monthly Costs (USD)

**Development/Testing:**

-   App Service (F1 Free): $0
-   PostgreSQL (Basic): ~$25-50
-   CosmosDB (Free tier): $0-25
-   **Total**: ~$25-75/month

**Production:**

-   App Service (B1 Basic): ~$13
-   PostgreSQL (General Purpose): ~$50-100
-   CosmosDB (Standard): ~$25-100
-   **Total**: ~$90-215/month

### Cost-Saving Tips

1. **Use Free Tiers**

    - App Service F1 (limited, but good for testing)
    - CosmosDB free tier (400 RU/s)

2. **Scale Down When Not Needed**

    - Use Azure Automation to scale down during off-hours

3. **Monitor Usage**

    - Set up budget alerts
    - Review Azure Cost Management regularly

4. **Optimize Database**
    - Use appropriate PostgreSQL tier
    - Configure CosmosDB autoscale

---

## Next Steps After Deployment

1. **Test Your Application**

    - Visit your Azure URL
    - Test all functionality
    - Verify both databases are working

2. **Set Up Monitoring**

    - Configure alerts for downtime
    - Set up performance monitoring

3. **Backup Strategy**

    - Enable automated backups for PostgreSQL
    - Configure CosmosDB backup

4. **Security Review**

    - Review network access rules
    - Set up proper authentication
    - Enable logging and monitoring

5. **Performance Optimization**
    - Enable caching (Redis if needed)
    - Optimize database queries
    - Set up CDN for static assets

---

## Support and Resources

-   **Azure Documentation**: [docs.microsoft.com/azure](https://docs.microsoft.com/azure)
-   **Laravel on Azure**: [laravel.com/docs/deployment](https://laravel.com/docs/deployment)
-   **Azure Support**: Available in Azure Portal
-   **Community**: Stack Overflow, Laravel Forums

---

**Congratulations!** 🎉 Your TechBuy Laravel application with dual database setup is now running on Azure!

Remember to:

-   ✅ Test all functionality after deployment
-   ✅ Set up monitoring and alerts
-   ✅ Configure proper backups
-   ✅ Review security settings
-   ✅ Monitor costs regularly

---

_Last updated: September 2025_
