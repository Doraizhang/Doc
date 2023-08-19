<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>编辑文档</title>
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
      background-image: url('https://t.mwm.moe/ycy');
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
      margin-right: 10px; /* 添加右边距 */
    }
    /* 设置按钮的悬停效果 */
    .btn-primary:hover {
      background-color: #9900cc;
      border-color: #9900cc;
    }
    /* 设置返回主页按钮的背景颜色和边框颜色 */
    .btn-secondary {
      background-color: #cc33ff;
      border-color: #cc33ff;
    }
    /* 设置返回主页按钮的悬停效果 */
    .btn-secondary:hover {
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
    
<?php
// 引入数据库配置文件
require_once 'config.php';

// 连接到数据库
$conn = new mysqli($hostname, $username, $password, $database);

// 检查连接是否成功
if ($conn->connect_error) {
  die("数据库连接失败: " . $conn->connect_error);
}

// 获取文档的ID，这里假设从查询字符串中获取
$docId = $_GET['id'];

// 根据文档的ID从数据库或其他数据源中获取文档的信息，包括文件名和内容
// 这里需要根据你的数据库结构和查询方式进行相应的实现
$sql = "SELECT * FROM saved_files WHERE id = $docId";
$result = $conn->query($sql);

// 检查是否找到文档
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $filename = $row['filename'];
  $content = $row['content'];

  // 检查是否提交了表单
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 处理表单提交的数据
    $filename = $_POST['filename'];
    $content = $_POST['content'];

    // 处理上传的图片
    if ($_FILES['image']['name']) {
      $image = $_FILES['image'];
      $image_name = $image['name'];
      $image_tmp = $image['tmp_name'];
      $image_path = "uploads/" . $image_name;

      // 检查文件格式
      $allowed_extensions = array('jpeg', 'jpg', 'png');
      $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
      if (in_array($file_extension, $allowed_extensions)) {
        // 将图片移动到指定的目录
        move_uploaded_file($image_tmp, $image_path);

        // 在内容中插入图片标签
        $content .= "<br><img src=\"$image_path\" alt=\"插入的图片\">";
      } else {
        echo "<script>alert('只允许上传 .jpeg、.jpg 或 .png 格式的图片')</script>"; // 使用 JavaScript 创建弹窗
      }
    }

    // 更新数据库中的文档信息
    $sql = "UPDATE saved_files SET filename = '$filename', content = '$content' WHERE id = $docId";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('文档已成功更新')</script>"; // 使用 JavaScript 创建弹窗
    } else {
      echo "<script>alert('更新文档时出现错误: " . $conn->error . "')</script>"; // 使用 JavaScript 创建弹窗
    }
  }

  echo "<body>";
  echo "<div class=\"container\">";
  echo "<img src=\"/Logo-3.png\" alt=\"元素云\" width=\"100%\" height=\"auto\">";
  echo "<h1 class=\"mt-5\">编辑文档</h1>";
  echo "<form method=\"POST\" action=\"\" enctype=\"multipart/form-data\">";
  echo "<div class=\"mb-3\">";
  echo "<label for=\"filename\" class=\"form-label\"><i class=\"fas fa-file\"></i> 文件名：</label>";
  echo "<input type=\"text\" class=\"form-control\" id=\"filename\" name=\"filename\" value=\"$filename\" required>";
  echo "</div>";
  echo "<div class=\"mb-3\">";
  echo "<label for=\"content\" class=\"form-label\"><i class=\"fas fa-edit\"></i> 内容：</label>";
  echo "<textarea class=\"form-control\" id=\"content\" name=\"content\" rows=\"5\" required>$content</textarea>";
  echo "</div>";
  echo "<div class=\"mb-3\">";
  echo "<label for=\"image\" class=\"form-label\"><i class=\"fas fa-image\"></i> 图片上传：</label>";
  echo "<input type=\"file\" class=\"form-control\" id=\"image\" name=\"image\" accept=\".jpeg, .jpg, .png\">"; // 添加 accept 属性限制文件格式
  echo "</div>";
  echo "<div class=\"mb-3\">";
  echo "<button type=\"submit\" class=\"btn btn-primary\">保存</button>";
  echo "<span>&nbsp;</span>"; // 添加一个空白占位符
  echo "<a href=\"index.php\" class=\"btn btn-secondary\">返回主页</a>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</body>";
} else {
  echo "<script>alert('无法找到指定的文档')</script>"; // 使用 JavaScript 创建弹窗
}

// 关闭数据库连接
$conn->close();
?>

<!-- 添加一个页脚，显示一些版权和联系信息 -->
<div class="footer">
  <p>© 2023 在线文档. All rights reserved. | Powered by 元素云 | Contact: zyx@elementweb.club</p>
</div>

<!-- 引入 Bootstrap 5 的 CDN 链接 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
