-- mysql -u root -p homestead < database/migrations/force_drop.sql
use homestead;
drop table if exists invoice_templates;
drop table if exists subscriptions;
drop table if exists emails;
drop table if exists invoice_items;
drop table if exists invoices;
drop table if exists invoice_item_categories;
drop table if exists password_resets;
drop table if exists role_user;
drop table if exists users;
drop table if exists settings;
drop table if exists companies;
drop table if exists roles;
drop table if exists jobs;
drop table if exists failed_jobs;
drop table if exists migrations;
