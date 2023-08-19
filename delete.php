<?php
// 引入数据库配置文件
require_once 'config.php';

// 检查是否提供了文档的 id 参数
if (isset($_GET['id'])) {
  // 获取文档 id
  $docId = $_GET['id'];

  // 连接到数据库
  $conn = new mysqli($hostname, $username, $password, $database);

  // 检查连接是否成功
  if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
  }

  // 构建删除文档的 SQL 查询语句
  $sql = "DELETE FROM saved_files WHERE id = $docId";

  // 执行 SQL 查询
  if ($conn->query($sql) === TRUE) {
    // 删除成功，跳转回文档列表页面
    header("Location: index.php");
    exit();
  } else {
    // 删除失败，显示错误消息
    echo "删除文档失败: " . $conn->error;
  }

  // 关闭数据库连接
  $conn->close();
} else {
  // 如果没有提供文档的 id 参数，显示错误消息
  echo "未指定要删除的文档。";
}
?>
