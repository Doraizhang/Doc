<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 获取表单提交的数据库信息
  $hostname = $_POST['hostname'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $database = $_POST['database'];

  // 进行数据库连接和初始化操作
  $conn = new mysqli($hostname, $username, $password, $database);

  // 检查连接是否成功
  if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
  }

  // 创建名为 'saved_files' 的表
  $sql = "CREATE TABLE IF NOT EXISTS saved_files (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    content TEXT
  )";

  if ($conn->query($sql) === TRUE) {
    echo "表 'saved_files' 创建成功！";

    // 导入数据库初始化脚本
    $sqlFile = file_get_contents('doc.sql');
    if ($conn->multi_query($sqlFile) === TRUE) {
      echo "数据库初始化成功！";
    } else {
      echo "导入数据库初始化脚本时出错: " . $conn->error;
    }
  } else {
    echo "创建表时出错: " . $conn->error;
  }

  // 关闭数据库连接
  $conn->close();
  
  // 写入数据库配置信息到 config.php 文件
  $configContent = "<?php\n";
  $configContent .= "// MySQL 配置信息\n";
  $configContent .= "\$hostname = \"$hostname\";\n";
  $configContent .= "\$username = \"$username\";\n";
  $configContent .= "\$password = \"$password\";\n";
  $configContent .= "\$database = \"$database\";\n";
  $configContent .= "?>";
  
  file_put_contents("config.php", $configContent);
  
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>在线文档安装</title>
  <!-- 引入 Bootstrap 5 的 CDN 链接 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- 引入 Font Awesome 5 的 CDN 链接，用于显示一些图标 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- 引入 Google Fonts 的 CDN 链接，用于设置字体 -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC:300,400,700|Noto+Serif+SC:300,400,700&display=swap" rel="stylesheet">
  <!-- 自定义 CSS 样式 -->
  <style>
    body {
      background-image: url('https://t.mwm.moe/bd');
      background-size:100% 100%;
      background-attachment:fixed;
      background-size: cover;
      background-repeat: no-repeat;
    }
    /* 设置全局字体 */
    * {
      font-family: 'Noto Sans SC', sans-serif;
    }
    /* 设置背景颜色为淡紫色 */
    body {
      background-color: #f0f0ff;
    }
    /* 设置容器的边距和阴影 */
    .container {
      margin-top: 50px;
      margin-bottom: 50px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    /* 设置标题的字体和颜色 */
    h1 {
      font-family: 'Noto Serif SC', serif;
      font-weight: bold;
      color: #660099;
    }
    /* 设置表单标签的字体和颜色 */
    .form-label {
      font-weight: bold;
      color: #330066;
    }
    /* 设置按钮的背景颜色和边框颜色 */
    .btn-primary {
      background-color: #cc33ff;
      border-color: #cc33ff;
    }
    /* 设置按钮的悬停效果 */
    .btn-primary:hover {
      background-color: #9900cc;
      border-color: #9900cc;
    }
    /* 设置页脚的字体、颜色和居中对齐 */
    .footer {
      font-size: 14px;
      color: #999999;
      text-align: center;
    }
    /* 设置页脚的超链接样式 */
    .footer a {
      color: #999999;
      text-decoration: none;
    }
    /* 设置页脚的超链接的悬停效果 */
    .footer a:hover {
      color: #660099;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- 添加一个二次元图片作为网页的顶部装饰 -->
    <img src="/Logo-3.png" alt="元素云" width="100%" height="auto">
    <h1 class="mt-5">在线文档安装</h1>
    <form method="post" action="install.php" class="mt-4">
      <div class="mb-3">
        <!-- 添加一个数据库图标 -->
        <label for="hostname" class="form-label"><i class="fas fa-database"></i> 数据库主机名：</label>
        <input type="text" name="hostname" id="hostname" class="form-control" required>
      </div>

      <div class="mb-3">
        <!-- 添加一个用户图标 -->
        <label for="username" class="form-label"><i class="fas fa-user"></i> 数据库用户名：</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <!-- 添加一个锁图标 -->
        <label for="password" class="form-label"><i class="fas fa-lock"></i> 数据库密码：</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>

      <div class="mb-3">
        <!-- 添加一个文件图标 -->
        <label for="database" class="form-label"><i class="fas fa-file"></i> 数据库名称：</label>
        <input type="text" name="database" id="database" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">安装</button>
    </form>
  </div>

  <!-- 添加一个页脚，显示一些版权和联系信息 -->
  <div class="footer">
    <p>© 2023 在线文档. All rights reserved. | Powered by 元素云 | Contact: zyx@elementweb.club</p>
  </div>

  <!-- 引入 Bootstrap 5 的 CDN 链接 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
