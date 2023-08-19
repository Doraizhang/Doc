<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>在线文档列表</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body {
      background-image: url('https://t.mwm.moe/ycy');
      background-size:100% 100%;
      background-attachment:fixed;
      background-size: cover;
      background-repeat: no-repeat;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      margin-top: 50px;
    }

    h1 {
      text-align: center;
      font-size: 24px;
      color: #333;
      margin-bottom: 20px;
    }

    ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    li {
      margin-bottom: 10px;
    }

    a {
      display: block;
      padding: 10px;
      background-color: #ffccff;
      color: #333;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    a:hover {
      background-color: #ff99ff;
    }

    p {
      text-align: center;
      color: #666;
    }

    .context-menu {
      display: none;
      position: absolute;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 5px;
      z-index: 9999;
    }

    .context-menu-item {
      cursor: pointer;
      padding: 5px;
    }

    .btn-create {
      display: block;
      margin-bottom: 10px;
      text-align: center;
    }

    .pagination {
      text-align: center;
      margin-top: 20px;
    }

    .pagination-info {
      display: inline-block;
      margin-right: 10px;
    }

    .pagination-buttons {
      display: flex;
      justify-content: flex-end; /* 将按钮放在最右边 */
      align-items: center; /* 垂直居中 */
    }

    .pagination-buttons a {
      display: inline-block;
      padding: 5px 10px;
      background-color: #ffccff; /* 与"新增文档"按钮相同的颜色 */
      color: #333;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
      margin-right: 5px; /* 添加按钮之间的间距 */
    }

    .pagination-buttons a:hover {
      background-color: #ff99ff;
    }

    .pagination-buttons .active {
      background-color: #ff99ff;
      color: #fff;
      cursor: default;
    }

    /* 新增样式 */
    .pagination-buttons .previous,
    .pagination-buttons .next {
      background-color: #ffccff;
      color: #333;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background-color 0.3s;
      margin-right: 5px;
    }

    .pagination-buttons .previous:hover,
    .pagination-buttons .next:hover {
      background-color: #ff99ff;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>在线文档列表</h1>
    <!-- 添加新增文档按钮 -->
    <a href="create.php" class="btn btn-primary btn-create">新增文档</a>
    <?php
    // 引入数据库配置文件
    require_once 'config.php';

    // 连接到数据库
    $conn = new mysqli($hostname, $username, $password, $database);

    // 检查连接是否成功
    if ($conn->connect_error) {
      die("数据库连接失败: " . $conn->connect_error);
    }

    // 查询数据库中的文档列表
    $sql = "SELECT * FROM saved_files";
    $result = $conn->query($sql);

    // 检查是否有文档存在
    if ($result->num_rows > 0) {
      // 获取当前页码，默认为第一页
      $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

      // 每页显示的文档数量
      $perPage = 8;

      // 计算总页数
      $totalPages = ceil($result->num_rows / $perPage);

      // 确保当前页码在有效范围内
      if ($currentPage < 1) {
        $currentPage = 1;
      } elseif ($currentPage > $totalPages) {
        $currentPage = $totalPages;
      }

      // 计算当前页应该显示的文档的起始位置和结束位置
      $start = ($currentPage - 1) * $perPage;
      $end = $start + $perPage - 1;

      // 重新查询数据库，限制结果集的范围
      $sql = "SELECT * FROM saved_files LIMIT $start, $perPage";
      $result = $conn->query($sql);

      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        $docId = $row['id'];
        $filename = $row['filename'];
        echo "<li><a href=\"view.php?id=$docId\" oncontextmenu=\"showContextMenu(event, $docId)\">$filename</a></li>";
      }
      echo "</ul>";

      // 输出分页导航栏
      echo "<div class=\"pagination\">";
      echo "<div class=\"pagination-info\">";
      echo "第 $currentPage 页 / 共 $totalPages 页";
      echo "</div>";
      echo "<div class=\"pagination-buttons\">";
      if ($currentPage > 1) {
        echo "<a href=\"?page=" . ($currentPage - 1) . "\" class=\"previous\">上一页</a>";
      }
      if ($currentPage < $totalPages) {
        echo "<a href=\"?page=" . ($currentPage + 1) . "\" class=\"next\">下一页</a>";
      }
      echo "</div>";
      echo "</div>";
    } else {
      echo "<p>暂无文档</p>";
    }

    // 关闭数据库连接
    $conn->close();
    ?>
  </div>

  <div class="context-menu" id="contextMenu">
    <div class="context-menu-item" onclick="editDocument()">编辑文档</div>
    <div class="context-menu-item" onclick="deleteDocument()">删除文档</div>
  </div>

  <script>
    function showContextMenu(event, docId) {
      event.preventDefault();

      const contextMenu = document.getElementById('contextMenu');
      contextMenu.style.left = event.clientX + 'px';
      contextMenu.style.top = event.clientY + 'px';
      contextMenu.style.display = 'block';

      // 将文档 id 存储在全局变量中，以便在删除文档函数中使用
      window.selectedDocId = docId;

      // 点击页面其他地方时隐藏上下文菜单
      document.addEventListener('click', hideContextMenu);
    }

    function hideContextMenu() {
      const contextMenu = document.getElementById('contextMenu');
      contextMenu.style.display = 'none';

      // 移除点击页面其他地方时隐藏上下文菜单的事件监听器
      document.removeEventListener('click', hideContextMenu);
    }

    function editDocument() {
      const docId = window.selectedDocId;
      window.location.href = 'edit.php?id=' + docId;
    }

    function deleteDocument() {
      if (window.confirm('确定要删除该文档吗？')) {
        // 发送删除文档的请求，可以使用 AJAX 或表单提交等方式
        // 这里只是一个示例，具体实现需要根据你的后端代码进行调整
        const docId = window.selectedDocId;
        window.location.href = 'delete.php?id=' + docId;
      }
    }
  </script>
</body>
</html>
