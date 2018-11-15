# Software-Sales-y
线上支付(Online payment)端，项目意在帮助小微软件开发者快速对接授权支付(Software sales)流程（本地激活(Local activation)，网络验证(Network verification)，支付对接(Payment)）。
## 运行环境
PHP7.0以上、Mysql3.2以上、建议开启 opcache（字节码缓存）。
## 修改配置
请在调试前程序前，先将 software_sales-y.sql 文件导入至数据库。  
根下 config.php 文件请遵循注释填写支付宝、密钥等配置信息。  
class/DB db_all.php 文件请遵循注释填写数据库连接信息。  
class/PHPMailer SendMailer.php 文件请遵循注释填写数据库连接信息。  

