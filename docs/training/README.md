# Spryker Backend Development Training

Complete training program for Spryker backend development covering 11 modules from basics to advanced topics.

## 📖 Main Manual

**[SPRYKER_TRAINING_MANUAL.md](./SPRYKER_TRAINING_MANUAL.md)** - Comprehensive 11-chapter guide

## 🎯 Training Modules

### Part 1: Basics (Day 1-2)
1. **Hello World Back Office** (30min) - First Zed controller
2. **Data Transfer Objects** (45min) - Type-safe data handling
3. **Database Schema - Message** (1hr) - Propel ORM basics
4. **Database Schema - Supplier** (1hr) - Complex schemas
5. **Module Layers Architecture** (2hrs) - Complete module structure

### Part 2: Intermediate (Day 3-5)
6. **Back Office CRUD** (3hrs) - Forms, tables, validation
7. **Data Import** (2hrs) - CSV import, data processors
8. **Publish & Synchronize** (3hrs) - Event-driven architecture
9. **Elasticsearch Integration** (3hrs) - Search implementation
10. **API Platform (Glue)** (3hrs) - REST API development
11. **Order Management System** (4hrs) - State machines, OMS

## 🚀 Getting Started

1. Clone this repository
2. For each module, checkout the `/skeleton` branch:
   \`\`\`bash
   git checkout ilt/202512.0/basics/hello-world-back-office/skeleton
   \`\`\`

3. Follow TODOs in the code

4. Compare with `/complete` branch for solutions

5. Refer to manual for detailed explanations

## 🌐 Environment URLs

Once your Spryker environment is running, you can access:

- **Back Office (Zed):** http://backoffice.eu.spryker.local/
  - Admin interface for managing products, orders, content, and configurations
  - Default credentials: admin@spryker.com / change123

- **Storefront (Yves):** http://yves.eu.spryker.local/
  - Customer-facing shop application
  - Where your APIs and frontend integrations are visible

- **Glue API:** http://glue.eu.spryker.local/
  - REST API endpoints for storefront and backend integrations

- **Elasticsearch:** http://localhost:9200
  - Search and analytics engine (accessible from within Docker)

## 📋 Branch Naming Convention

\`\`\`
ilt/202512.0/{level}/{topic}/{version}

Examples:
- ilt/202512.0/basics/hello-world-back-office/skeleton
- ilt/202512.0/basics/hello-world-back-office/complete
- ilt/202512.0/intermediate/oms/skeleton
- ilt/202512.0/intermediate/oms/complete
\`\`\`

## 📚 Learning Path

**Recommended Order:**
1. Start with Part 1 (Basics) - builds foundation
2. Complete each skeleton exercise
3. Check your work against complete branch
4. Move to Part 2 (Intermediate) - real-world features

**Time Commitment:**
- Full-time: 3-5 days
- Part-time: 2-3 weeks

## 🎓 Prerequisites

- PHP 8.1+
- Object-Oriented Programming
- MVC Pattern
- Basic SQL
- Docker
- Git

## 📞 Support

- **Spryker Documentation**: https://docs.spryker.com
- **Spryker Academy**: https://academy.spryker.com
- **Community Slack**: https://sprykercommunity.slack.com

## ✅ Completion Checklist

- [ ] Hello World Back Office
- [ ] Data Transfer Objects
- [ ] Database Schema - Message
- [ ] Database Schema - Supplier
- [ ] Module Layers Architecture
- [ ] Back Office CRUD
- [ ] Data Import
- [ ] Publish & Synchronize
- [ ] Elasticsearch Integration
- [ ] API Platform (Glue)
- [ ] Order Management System

---

**Happy Learning! 🚀**

