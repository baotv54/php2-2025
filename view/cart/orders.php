  <style>
      body {
          background-color: #f5f5f5;
          padding: 20px;
      }

      .container {
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      h2 {
          color: #ee4d2d;
      }

      .btn-primary {
          background: #ee4d2d;
          border: none;
      }

      .btn-primary:hover {
          background: #d4371c;
      }
  </style>

  
      <h2>Đơn hàng của bạn</h2>
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Mã đơn hàng</th>
                  <th>Mã giao dịch</th>
                  <th>Tổng tiền</th>
                  <th>Địa chỉ</th>
                  <th>Ghi chú</th>
                  <th>Trạng thái</th>
                  <th>Phương thức thanh toán</th>
                  <th>Ngày đặt hàng</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($orders as $order): ?>
                  <tr>
                      <td><?= htmlspecialchars($order['id']) ?></td>
                      <td><?= htmlspecialchars($order['code']) ?></td>
                      <td><?= number_format($order['total'], 2) ?></td>
                      <td><?= htmlspecialchars($order['address']) ?></td>
                      <td><?= htmlspecialchars($order['note']) ?></td>
                      <td><?= htmlspecialchars($order['status']) ?></td>
                      <td><?= htmlspecialchars($order['paymentMethod']) ?></td>
                      <td><?= htmlspecialchars($order['createDate']) ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
      <a href="/" class="btn btn-primary">Quay lại trang chủ</a>
