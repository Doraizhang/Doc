<?php
// 引入数据库配置文件
require_once 'config.php';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 获取表单数据
  $filename = $_POST['filename'];
  $content = $_POST['content'];

  // 连接到数据库
  $conn = new mysqli($hostname, $username, $password, $database);

  // 检查连接是否成功
  if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
  }

  // 插入文档数据到数据库
  $sql = "INSERT INTO saved_files (filename, content) VALUES ('$filename', '$content')";
  if ($conn->query($sql) === TRUE) {
    // 文档创建成功后自动跳转回主页
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
  } else {
    echo "文档创建失败: " . $conn->error;
  }

  // 关闭数据库连接
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>创建文档</title>
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
      border-radius: 10px;
    }
    /* 设置标题的字体和颜色 */
    h1 {
      font-family: 'Noto Serif SC', serif;
      font-weight: bold;
      color: #660099;
      text-align: center;
    }
    /* 设置表单标签的字体和颜色 */
    .form-label {
      font-weight: bold;
      color: #330066;
    }
    /* 设置输入框的边框颜色和圆角 */
    .form-control {
      border-color: #cc33ff;
      border-radius: 5px;
    }
    /* 设置输入框的悬停效果 */
    .form-control:hover {
      border-color: #9900cc;
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
    <h1 class="mt-5">创建文档</h1>
    <form method="POST" action="">
      <div class="mb-3">
        <!-- 添加一个文件图标 -->
        <label for="filename" class="form-label"><i class="fas fa-file"></i> 文件名：</label>
        <input type="text" class="form-control" id="filename" name="filename" required>
      </div>
      <div class="mb-3">
        <!-- 添加一个编辑图标 -->
        <label for="content" class="form-label"><i class="fas fa-edit"></i> 内容：</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">创建</button>
    </form>
  </div>

  <!-- 添加一个页脚，显示一些版权和联系信息 -->
  <div class="footer">
    <p>© 2023 在线文档. All rights reserved. | Powered by 元素云 | Contact: zyx@elementweb.club</p>
  </div>

  <!-- 引入 Bootstrap 5 的 CDN 链接 -->
  <script src="/Logo-3.png"></script>
</body>
</html>
