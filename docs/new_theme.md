Hãy sử dụng skill `frontend-design` để thiết kế và triển khai lại giao diện trên source WordPress theme hiện tại.

Đây là dự án làm mới giao diện dựa trên theme có sẵn. Mục tiêu là tạo trải nghiệm mới, giữ nguyên toàn bộ tính năng và khả năng quản trị hiện có.

1. KHẢO SÁT TRƯỚC KHI SỬA

- Đọc CLAUDE.md, AGENTS.md và các quy tắc liên quan trong repo.
- Đọc hướng dẫn của skill frontend-design trước khi thiết kế. Nếu skill không khả dụng, báo rõ.
- Kiểm tra template, CSS, JavaScript, nguồn dữ liệu và các thành phần dùng chung.
- Xác định những class, ID, data attribute đang được JavaScript hoặc plugin sử dụng.
- Ghi nhận giao diện hiện tại của Header/Footer trên desktop và mobile làm mốc đối chiếu.
- Bảo toàn các thay đổi có sẵn trong workspace.

2. MỤC TIÊU THIẾT KẾ

- Chủ đề: phòng khám nha khoa tại Cần Thơ.
- Đối tượng: người trưởng thành khoảng 27–60 tuổi, ưu tiên trải nghiệm dễ đọc và dễ thao tác.
- Cảm nhận: chuyên nghiệp, đáng tin cậy, gần gũi, sạch sẽ.
- Mục tiêu sử dụng: tìm hiểu dịch vụ, xem thông tin bác sĩ, đọc kiến thức nha khoa, gọi điện và đặt lịch.
- Tiếp tục sử dụng duy nhất font Roboto tự host.
- Tự chọn bảng màu cho phần nội dung mới, hài hòa với Header/Footer hiện tại.
- Thiết kế lại bố cục, phân cấp thông tin, typography, khoảng cách, hình ảnh và component; không chỉ thay màu.
- Có bản sắc phù hợp nha khoa, tránh chia mọi nội dung thành các thẻ giống nhau hoặc lạm dụng hiệu ứng trang trí.
- Ưu tiên nội dung dễ đọc, tương phản tốt, nút rõ ràng và chuyển động tiết chế.

3. PHẠM VI THỰC HIỆN

Thiết kế lại đầy đủ 8 loại trang:

1) Trang chủ.
2) Chi tiết bài viết — Single Post.
3) Trang 404.
4) Liên hệ – Đặt lịch.
5) Danh mục bài viết — Category.
6) Kết quả tìm kiếm — Search.
7) Hồ sơ bác sĩ.
8) Danh sách bác sĩ.

Các trang thường và landing page ngoài danh sách trên giữ giao diện hiện tại. Kiểm tra chúng khi thay đổi thành phần dùng chung để tránh ảnh hưởng ngoài phạm vi.

GIỮ NGUYÊN HOÀN TOÀN HEADER VÀ FOOTER:
- Giữ bố cục, màu sắc, kích thước, logo, typography và khoảng cách.
- Giữ thiết kế responsive, sticky header, menu mobile và các hành vi hiện có.
- Không để CSS, token màu hoặc component mới vô tình làm thay đổi chúng.

4. BẢO TOÀN TÍNH NĂNG VÀ DỮ LIỆU

- Giữ nguyên dữ liệu WordPress, nội dung, media, menu, Customizer và ACF.
- Giữ nguyên CPT, taxonomy, field key, shortcode, theme mod, URL và permalink.
- Giữ tên thư mục theme, text domain và tiền tố hàm/hằng hiện tại để bảo toàn tương thích.
- Giữ mọi tùy chọn bật/tắt, lựa chọn nội dung và sắp xếp đang có trong quản trị.
- Giữ tìm kiếm, phân trang, lọc danh mục, slider, video/bản đồ lazy load.
- Giữ mục lục, người tham vấn, FAQ, tài liệu tham khảo và đánh giá bài viết.
- Giữ đầy đủ các phần dữ liệu của hồ sơ bác sĩ.
- Giữ Fluent Forms, popup đặt lịch, nút liên hệ nhanh và logic chuyển đến form/anchor.
- Giữ SEO, schema, WebMCP và các tích hợp plugin hiện có.
- Không thay dữ liệu động bằng nội dung hardcode.
- Không sửa database hoặc reset cấu hình để phục vụ việc thiết kế.

Có thể thay markup PHP và JavaScript phục vụ trình bày, nhưng phải bảo toàn hành vi và dữ liệu. Nếu đổi selector, cập nhật đồng bộ mọi nơi sử dụng.

5. LÀM SẠCH GIAO DIỆN CŨ

Trước khi dựng từng phần mới, xác định rõ phần giao diện cũ nào sẽ được thay thế và phần nào còn được dùng chung.

- Thay thế và loại bỏ markup trình bày, CSS và JavaScript thuần trang trí đã hết sử dụng trong phạm vi thiết kế lại.
- Không giữ hai bộ giao diện cũ/mới chạy song song rồi dùng nhiều lớp CSS ghi đè.
- Không xóa toàn bộ src/, template-parts/, inc/ hoặc asset một cách hàng loạt.
- Không xóa component, selector hoặc asset vẫn được Header/Footer, trang ngoài phạm vi hoặc tính năng hiện tại sử dụng.
- Với thành phần dùng chung, tách phạm vi style hoặc biến thể trình bày khi cần.
- Chỉ xóa mã sau khi kiểm tra các nơi tham chiếu.

Kết quả phải có cấu trúc giao diện rõ ràng, không tích lũy các lớp sửa chồng lên giao diện cũ.

6. YÊU CẦU TRIỂN KHAI

- Tiếp tục dùng kiến trúc Classic WordPress theme, Vite, Tailwind và JavaScript hiện tại.
- Sửa asset trong src/ rồi build; không sửa trực tiếp dist/.
- Giữ cơ chế chia bundle và giới hạn utility theo vùng DOM.
- Không đổi token toàn cục nếu việc đó làm thay đổi Header/Footer hoặc các trang ngoài phạm vi.
- Tiếp tục dùng helper của theme cho ảnh, icon, đặt lịch, form và link external.
- Giữ tối ưu ảnh LCP, responsive images, lazy loading, font và cơ chế tải script hiện tại.
- Tái sử dụng nội dung, hình ảnh và dữ liệu thực đang có.
- Không tự bịa chứng chỉ, số liệu khách hàng, đánh giá hoặc cam kết điều trị.
- Không thêm thư viện lớn hay framework mới khi stack hiện tại đáp ứng được.

7. VERSION VÀ CLAUDE.md

- Đặt Version trong style.css thành 1.0.0 cho đợt giao diện mới.
- Bắt đầu lịch sử phiên bản mới trong CLAUDE.md từ 1.0.0.
- Reset số phiên bản không được loại bỏ hoặc hoàn tác bất kỳ tính năng đang có nào.
- Chuyển lịch sử phiên bản cũ sang tài liệu lưu trữ trong docs/, giữ nguyên các bản zip cũ.
- Cập nhật CLAUDE.md theo triển khai thực tế: định hướng thiết kế, cấu trúc giao diện, component, phạm vi được bảo vệ, cách build và kiểm tra.
- Giữ lại hướng dẫn kỹ thuật và tích hợp vẫn còn hiệu lực; sửa các mô tả giao diện đã lỗi thời.

8. KIỂM TRA VÀ BÀN GIAO

- Build thành công; kiểm tra cú pháp PHP và JavaScript liên quan.
- Kiểm tra đủ 8 loại trang trên mobile, tablet và desktop.
- Kiểm tra Header/Footer trước và sau ở cùng kích thước màn hình.
- Không có tràn ngang, nội dung bị che hoặc lỗi console phát sinh.
- Kiểm tra nội dung dài, bảng, ảnh thiếu, section thiếu dữ liệu, tìm kiếm không có kết quả và phân trang.
- Kiểm tra menu mobile, sticky header, popup, form validation, anchor đặt lịch, slider và các chức năng chính.
- Đảm bảo thao tác bàn phím, focus rõ, tương phản đạt WCAG AA và tôn trọng reduced motion.
- Sau build, xóa page cache local bằng cơ chế hiện có nếu truy cập được.
- Nếu môi trường không cho phép kiểm tra phần nào, báo rõ phần chưa xác minh; không tuyên bố đã kiểm tra.
- Bàn giao tóm tắt thay đổi, phần giao diện cũ đã loại bỏ, kết quả kiểm tra và giới hạn còn lại.

Trước khi code, trình bày ngắn hướng thiết kế, bảng màu, thang chữ và bố cục dự kiến. Tự đối chiếu với brief rồi triển khai đầy đủ. Chỉ hỏi thêm khi có thông tin quan trọng không thể xác định từ source hoặc yêu cầu này.