<?php
/**
 * 环境说明：
 * PRODUCTION = 生产环境 production
 * STAGING （这个不清楚）
 * TESTING = 测试环境（75服务器用） testing
 * DEVELOPMENT = 开发环境  development
 * author: edgeto
 * time: 2017-02-08 17:00
 */
// Common environment type constants for consistency and convenience
const PRODUCTION  = 10;
const STAGING     = 20;
const TESTING     = 30;
const DEVELOPMENT = 40;
//设置环境
define('ENVIRONMENR','PRODUCTION');
