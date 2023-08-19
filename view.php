<?php
// 引入数据库配置文件
require_once 'config.php';

// 检查是否传递了文档的 id 参数
if (isset($_GET['id'])) {
  $docId = $_GET['id'];

  // 连接到数据库
  $conn = new mysqli($hostname, $username, $password, $database);

  // 检查连接是否成功
  if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
  }

  // 查询指定 id 的文档
  $sql = "SELECT * FROM saved_files WHERE id = $docId";
  $result = $conn->query($sql);

  // 检查是否找到了文档
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $filename = $row['filename'];
    $content = $row['content'];
  } else {
    echo "<p>未找到指定的文档。</p>";
    exit;
  }

  // 关闭数据库连接
  $conn->close();
} else {
  echo "<p>未提供文档的 id 参数。</p>";
  exit;
}

$filename = isset($filename) ? $filename : '';
$content = isset($content) ? $content : '';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $filename; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- 引入 Bootstrap 5 的 CDN 链接 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- 引入 Google Fonts 的 CDN 链接，用于设置字体 -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC:300,400,700|Noto+Serif+SC:300,400,700&display=swap" rel="stylesheet">
  <!-- 自定义 CSS 样式 -->
  <style>
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
      max-width: 100%;
      margin: 0 auto;
      padding: 10px;
      background-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 5px;
      margin-top: 20px;
    }
    /* 设置标题的字体和颜色 */
    h1 {
      font-family: 'Noto Serif SC', serif;
      font-weight: bold;
      color: #660099;
      text-align: center;
      margin-bottom: 10px;
      animation: slide-down 1s ease-in-out;
    }
    /* 设置文档内容的样式 */
    .document-content {
      background-color: #f8f8f8;
      padding: 10px;
      border-radius: 5px;
      font-family: Arial, sans-serif;
      font-size: 14px;
      line-height: 1.5;
      white-space: pre-wrap; /* 添加换行显示 */
      animation: slide-up 1s ease-in-out;
    }

    /* 添加一个二次元图片作为网页的顶部装饰 */
    img {
      max-width: 100%;
      height: auto;
    }

    /* 修改文档内容的背景颜色和边框 */
    .document-content {
      background-color: #ffccff;
      border: 2px solid #cc33ff;
    }

    /* 修改文档内容的字体颜色和大小 */
    .document-content {
      color: #333;
      font-size: 16px;
    }

    /* 添加一些动画效果 */
    .container {
      animation: fade-in 1s ease-in-out;
    }

    @keyframes fade-in {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slide-down {
      from {
        transform: translateY(-100%);
      }
      to {
        transform: translateY(0);
      }
    }

    @keyframes slide-up {
      from {
        transform: translateY(100%);
      }
      to {
        transform: translateY(0);
      }
    }

    /* 添加媒体查询，调整样式以适应移动设备 */
    @media (max-width: 768px) {
      /* 移动端样式 */
      body {
      background-image: url('https://t.mwm.moe/ycy');
      background-size:100% 100%;
      background-attachment:fixed;
      background-size: cover;
      background-repeat: no-repeat;
    }
      
      body {
        background-color: #ffffff;
      }
      .container {
        background-color: #ffffff;
        box-shadow: none;
        margin-top: 10px;
      }
      h1 {
        font-size: 24px;
        margin-bottom: 5px;
      }
      .document-content {
        font-size: 16px;
      }
    }

    /* 添加媒体查询，调整样式以适应PC端 */
    @media (min-width: 769px) {
      /* PC端样式 */
    body {
      background-image: url('https://t.mwm.moe/ycy');
      background-size:100% 100%;
      background-attachment:fixed;
      background-size: cover;
      background-repeat: no-repeat;
    }
      .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 5px;
      margin-top: 50px;
    }
    /* 设置标题的字体和颜色 */
    h1 {
      font-family: 'Noto Serif SC', serif;
      font-weight: bold;
      color: #660099;
      text-align: center;
      margin-bottom: 20px;
      animation: slide-down 1s ease-in-out;
    }
    /* 设置文档内容的样式 */
    .document-content {
      background-color: #f8f8f8;
      padding: 20px;
      border-radius: 5px;
      font-family: Arial, sans-serif;
      font-size: 16px;
      line-height: 1.5;
      white-space: pre-wrap; /* 添加换行显示 */
      animation: slide-up 1s ease-in-out;
    }

    /* 添加一个二次元图片作为网页的顶部装饰 */
    img {
      width: 100%;
      height: auto;
    }

    /* 修改文档内容的背景颜色和边框 */
    .document-content {
      background-color: #ffffff;
      border: 5px solid #ffffff;
    }

    /* 修改文档内容的字体颜色和大小 */
    .document-content {
      color: #333;
      font-size: 18px;
    }

    /* 添加一些动画效果 */
    .container {
      animation: fade-in 1s ease-in-out;
    }

    @keyframes fade-in {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slide-down {
      from {
        transform: translateY(-100%);
      }
      to {
        transform: translateY(0);
      }
    }

    @keyframes slide-up {
      from {
        transform: translateY(100%);
      }
      to {
        transform: translateY(0);
      }
    }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="container">
      <a href="index.php" class="btn btn-primary">返回主页</a> <!-- 添加返回主页按钮 -->
      <div class="document-header">
        <h1><?php echo $filename; ?></h1>
      </div>
      <div class="document-content">
        <div v-html="formattedContent"></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        content: `<?php echo $content; ?>`
      },
      computed: {
        formattedContent() {
          // 在这里可以对文档内容进行格式化，例如添加样式、处理链接等
          return this.content;
        }
      }
    });
  </script>
</body>
</html>
