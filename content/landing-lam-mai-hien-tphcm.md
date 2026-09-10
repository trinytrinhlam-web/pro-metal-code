# Nội dung Landing: "Làm Mái Hiên, Mái Che Tại TP.HCM"

Nội dung dưới đây được soạn theo đúng "khuôn" (khối/field) mà theme Pro-Metal
đang dùng chung cho mọi landing page (`template-landing.php` +
`inc/fields.php` → Carbon Fields `landing_blocks`). Cứ điền đúng khối/field
là trang sẽ tự lên **y hệt bố cục, màu sắc, component** với các LP khác đang
chạy trên site (hero tối, trust bar xanh brand, thẻ ứng dụng nền sáng, bảng
giá, quy trình, khu vực/chi nhánh, FAQ accordion, form báo giá) — không cần
đụng code.

> Ghi chú: phiên làm việc này không truy cập được `suachuacuasat.com` (egress
> bị chặn ở môi trường sandbox), nên không lấy nguyên văn được nội dung đang
> có ở trang `/lam-mai-hien-tphcm/`. Nội dung bên dưới soạn mới, bám theo đúng
> văn phong/độ dài/cấu trúc mà theme đang dùng làm mẫu chuẩn (khối "Vách ngăn
> panel" hiện là default trong `template-parts/landing/*.php`) và điều
> chỉnh cho đúng chủ đề mái hiên/mái che. Anh chị đọc lại, chỉnh số liệu/giá
> trị thực tế (giá, thời gian, bảo hành...) trước khi đăng.

## Cách dùng
1. WP Admin → **Trang** → Thêm trang mới, chọn Template = **Landing**.
2. Đặt URL/slug (gợi ý giữ đúng slug đang dùng hoặc `/lam-mai-hien-tphcm/`).
3. Ở khung **"Nội dung Landing (Pro-Metal)"**, thêm lần lượt các khối theo
   đúng thứ tự bên dưới (kéo–thả để sắp xếp), copy nội dung vào từng field.
4. Ảnh: theme đã có ảnh mẫu bundle sẵn nên bỏ trống vẫn lên trang bình
   thường — nhưng nên thay bằng ảnh công trình mái hiên thật để tăng độ tin
   cậy (đặc biệt khối Hero, Quy trình, Ảnh thực tế, Form báo giá).
5. Khối **Khu vực & chi nhánh**: có thể để trống phần "Khu vực" và "Chi
   nhánh" — mặc định theme đã tự điền đúng danh sách quận/chi nhánh chung của
   Pro-Metal (không cần nhập lại), chỉ cần điền `heading`/`lead`.

---

## 1) Khối: Hero dịch vụ (`hero`)

**Tiêu đề H1**
```
Làm Mái Hiên, Mái Che Tại TP.HCM
```

**Mô tả ngắn**
```
Pro-Metal nhận thi công mái hiên, mái che cho nhà phố, sân thượng, ban công,
mái để xe và quán ăn/sân vườn tại TP.HCM. Khảo sát tận nơi, tư vấn loại mái
phù hợp và báo giá theo diện tích, vật tư thực tế trước khi làm.
```

**Gạch đầu dòng (USP)** — 4 dòng
```
Tư vấn tôn, polycarbonate, bạt kéo hay kính cường lực theo đúng nhu cầu che nắng/che mưa
Khung sắt hộp mạ kẽm, sơn tĩnh điện, chống gỉ, chịu được mưa nắng lâu dài
Báo giá theo diện tích và vật tư thực tế, rõ hạng mục trước khi làm
Nhận khảo sát tại TP.HCM, thi công gọn, có bảo hành
```

**Ảnh minh hoạ**: upload 1 ảnh mái hiên thực tế đã làm (ngang, sáng, rõ khung
+ mái lợp).

---

## 2) Khối: Thanh niềm tin (`trust_bar`) — 4 chỉ số

| Dòng nổi bật | Ghi chú |
|---|---|
| Khảo sát tận nơi | đo đạc, tư vấn miễn phí |
| Thi công nhanh | 1–3 ngày với mái hiên phổ thông |
| Nhiều loại mái | tôn, polycarbonate, bạt, kính |
| 03.4444.07.17 | hotline & Zalo |

---

## 3) Khối: Đoạn mở đầu (`intro`)

```
Mái hiên, mái che phù hợp cho nhà phố cần chắn nắng, chắn mưa hắt vào ban
công, sân thượng, lối đi mà không phải xây thêm tường hay đổ mái bê tông. So
với xây dựng cố định, làm mái hiên bằng khung sắt kết hợp tôn, polycarbonate
hoặc bạt kéo linh hoạt hơn, thi công nhanh và ít ảnh hưởng kết cấu nhà.

Tuy vậy, mỗi vị trí lắp mái hiên có yêu cầu khác nhau. Mái hiên trước nhà cần
tính độ dốc thoát nước và khoảng đưa ra hợp lý. Mái che sân thượng cần chú ý
chống thấm điểm neo và chịu được gió. Mái để xe cần khung chắc, đủ chiều cao
ra vào. Mái che quán ăn, sân vườn lại cần thêm yếu tố thẩm mỹ và ánh sáng.

Pro-Metal đi theo hướng tư vấn trước khi thi công: xem vị trí lắp, hướng
nắng/mưa, kết cấu tường/cột hiện trạng, rồi mới đề xuất loại mái, khung và
cách hoàn thiện phù hợp.
```

---

## 4) Khối: Thẻ ứng dụng (`cards`)

**Tiêu đề khối**
```
Mái hiên, mái che phù hợp không gian nào?
```

**Mô tả khối**
```
Mỗi vị trí có một cách làm khác nhau. Chúng tôi ưu tiên tư vấn theo mục đích
sử dụng và hướng nắng/mưa thực tế.
```

**Thẻ** (tiêu đề — mô tả), 6 thẻ:
```
Mái hiên trước nhà — Che nắng, che mưa hắt cho cửa chính, ban công tầng trệt. Cần tính độ dốc, khoảng đưa ra và không che khuất mặt tiền.

Mái che sân thượng — Tận dụng sân thượng làm nơi phơi đồ, thư giãn, tiểu cảnh. Cần xử lý điểm neo, chống thấm sàn và chịu được gió mạnh.

Mái che ban công — Ngăn nắng, mưa hắt vào phòng, tăng diện tích sử dụng ban công. Ưu tiên khung gọn, ít che tầm nhìn.

Mái để xe, gara — Che mưa nắng cho ô tô, xe máy trước nhà hoặc trong xưởng. Cần khung chắc, đủ độ cao ra vào và độ dốc thoát nước tốt.

Mái che quán ăn, sân vườn — Kết hợp lấy sáng bằng polycarbonate hoặc kính, tạo không gian thoáng, hợp phong cách quán/nhà hàng.

Mái che lối đi, hành lang — Nối liền các khu vực trong nhà xưởng hoặc nhà phố, hạn chế ướt khi di chuyển ngày mưa.
```

---

## 5) Khối: Thư viện ảnh (`gallery`)

**Tiêu đề khối**
```
Ảnh thực tế mái hiên đã thi công
```

**Mô tả khối**
```
Anh chị có thể gửi thêm ảnh mặt bằng hoặc vị trí lắp qua Zalo để chúng tôi tư
vấn loại mái, độ dốc và cách lắp phù hợp.
```

**Ảnh**: upload 6 ảnh công trình mái hiên thật (trước–sau nếu có), kèm chú
thích ngắn mỗi ảnh, ví dụ: "Mái hiên trước nhà khung sắt + tôn", "Mái che
sân thượng polycarbonate lấy sáng", "Mái bạt kéo ban công", "Mái để xe khung
hộp mạ kẽm"...

---

## 6) Khối: Bảng loại panel → dùng cho "Các loại mái hiên" (`price_table`)

**Tiêu đề khối**
```
Các loại mái hiên thường dùng
```

**Mô tả khối**
```
Không có loại mái tốt nhất cho mọi công trình. Loại phù hợp là loại đáp ứng
đúng nhu cầu che nắng/che mưa, thẩm mỹ và ngân sách.
```

**Dòng bảng** (Tên hạng mục / Phù hợp / Ghi chú tư vấn / Mức chi phí):

| Tên hạng mục | Phù hợp | Ghi chú tư vấn | Mức chi phí |
|---|---|---|---|
| Mái tôn | Mái hiên, mái để xe, mái che sân thượng cần che mưa nắng chắc chắn | Bền, chi phí hợp lý, nhiều màu và loại tôn cách nhiệt để chọn. | Hợp lý |
| Mái polycarbonate (lấy sáng) | Mái che sân thượng, ban công, quán cà phê cần vừa che mưa vừa lấy sáng tự nhiên | Nhẹ, cho ánh sáng qua, nên chọn loại dày, chống UV để bền màu. | Cao hơn |
| Mái bạt kéo (mái xếp) | Ban công, sân thượng, quán ăn cần đóng/mở linh hoạt theo thời tiết | Linh hoạt, gọn khi không dùng, cần bảo trì ray trượt và bạt định kỳ. | Cao hơn |
| Mái kính cường lực | Mái hiên nhà phố, sảnh cần độ bền cao và thẩm mỹ hiện đại | Chịu lực tốt, sang trọng, chi phí và yêu cầu kỹ thuật lắp đặt cao hơn. | Theo yêu cầu |

**CTA — tiêu đề**
```
Không chắc nên dùng loại mái nào?
```
**CTA — mô tả**
```
Gửi ảnh vị trí lắp hoặc kích thước, chúng tôi xem nhu cầu rồi tư vấn loại
phù hợp trước khi báo giá.
```
**CTA — nhãn nút**: `Gửi qua Zalo` — **Link**: để trống (tự dùng Zalo mặc định).

---

## 7) Khối: Yếu tố chi phí (`cost_factors`)

**Tiêu đề khối**
```
Chi phí làm mái hiên phụ thuộc điều gì?
```

**Mô tả khối**
```
Pro-Metal không báo giá cố định vì chi phí thay đổi theo vật tư, diện tích
và hiện trạng. Cách báo đúng là khảo sát hoặc xem ảnh/kích thước thực tế.
```

**Yếu tố** (tiêu đề — mô tả), 6 mục:
```
Loại vật tư mái — Tôn, polycarbonate, bạt kéo hay kính cường lực có mức chi phí và độ bền khác nhau.

Diện tích và khoảng đưa ra — Mái càng rộng, khoảng đưa ra càng xa thì khung càng lớn, kéo theo vật tư và công lắp nhiều hơn.

Kết cấu điểm neo — Tường, cột hiện trạng chắc sẽ lắp nhanh hơn. Vị trí cần khoan neo vào bê tông, xử lý chống thấm sẽ tăng công.

Độ dốc và thoát nước — Mái cần tính độ dốc phù hợp để thoát nước tốt, tránh đọng nước gây võng, dột về sau.

Vị trí thi công — Nhà phố hẻm nhỏ, tầng cao hoặc khu vực khó bắc giàn giáo sẽ có cách tổ chức thi công khác mặt bằng trống.

Mức hoàn thiện — Sơn tĩnh điện khung, phụ kiện inox, máng xối và yêu cầu thẩm mỹ ảnh hưởng đến chi phí sau cùng.
```

---

## 8) Khối: Quy trình thi công (`process`)

**Tiêu đề khối**
```
Quy trình thi công mái hiên, mái che
```

**Mô tả khối**
```
Quy trình rõ ràng từ đầu để anh chị nắm được vật tư, thời gian và chi phí
trước khi thi công.
```

**Ảnh minh hoạ**: ảnh khung mái hiên đang lắp dựng hoặc toàn cảnh công trình.

**Bước** (tên bước — mô tả), 5 bước:
```
Tiếp nhận nhu cầu — Gửi vị trí lắp, kích thước ước lượng và mục đích sử dụng (che nắng, che mưa, lấy sáng...).

Khảo sát tận nơi — Đo đạc thực tế, xem kết cấu tường/cột, hướng nắng mưa và đường thoát nước.

Tư vấn & báo giá — Đề xuất loại mái, khung, độ dốc phù hợp; tách rõ vật tư, công lắp và thời gian hoàn thành.

Gia công & lắp đặt — Gia công khung tại xưởng, vận chuyển và lắp dựng tại công trình, xử lý điểm neo và mái lợp.

Nghiệm thu & bàn giao — Kiểm tra độ chắc chắn, thoát nước, vệ sinh công trình và hướng dẫn bảo quản.
```

---

## 9) Khối: Cam kết (`commitment`)

**Tiêu đề khối**
```
Cam kết khi nhận công trình mái hiên
```

**Cam kết** (tiêu đề — mô tả), 4 mục:
```
Tư vấn đúng nhu cầu — Không đẩy loại mái vượt yêu cầu sử dụng chỉ để tăng chi phí.

Báo rõ vật tư — Loại tôn/poly/bạt/kính, khung, phụ kiện và phần phát sinh được nói rõ trước khi làm.

Thi công gọn, đúng hẹn — Sắp xếp thợ và vật tư hợp lý, hạn chế ảnh hưởng sinh hoạt của gia đình.

Phát sinh phải hỏi trước — Không tự làm thêm rồi tính thêm khi chưa thống nhất với anh chị.
```

**Ô lợi ích (icon)** — 4 nhãn: `Chắn nắng tốt`, `Chống dột`, `Khung bền, chống gỉ`, `Thi công nhanh`

---

## 10) Khối: Khu vực & chi nhánh (`areas`)

**Tiêu đề khối**
```
Khu vực nhận thi công mái hiên tại TP.HCM
```

**Mô tả khối**
```
Pro-Metal nhận khảo sát tại nhiều khu vực TP.HCM. Với công trình ngoài
TP.HCM, anh chị gửi vị trí để chúng tôi xem khả năng nhận thi công.
```

**Khu vực / Chi nhánh**: để trống — theme tự điền đúng danh sách quận và 3
chi nhánh chung của Pro-Metal (không riêng cho dịch vụ nào), khỏi nhập lại.

---

## 11) Khối: FAQ (`faq`)

**Tiêu đề khối**
```
Câu hỏi thường gặp về mái hiên, mái che
```

**Câu hỏi / Trả lời**, 6 mục:

**Thi công mái hiên mất bao lâu?**
```
Mái hiên phổ thông (tôn, polycarbonate) diện tích vừa phải thường hoàn
thành trong 1–3 ngày. Mái bạt kéo, mái kính hoặc vị trí khó thi công (tầng
cao, hẻm nhỏ) cần thêm thời gian.
```

**Nên chọn mái tôn hay mái polycarbonate?**
```
Mái tôn che mưa nắng chắc chắn, chi phí hợp lý nhưng không lấy sáng. Mái
polycarbonate cho ánh sáng tự nhiên, phù hợp ban công/sân thượng nhưng chi
phí cao hơn tôn. Tuỳ nhu cầu che tối hay lấy sáng để chọn loại phù hợp.
```

**Mái hiên có chịu được mưa gió lớn không?**
```
Khung sắt hộp mạ kẽm, neo đúng kỹ thuật và độ dốc thoát nước hợp lý sẽ chịu
được điều kiện thời tiết TP.HCM thông thường. Với khu vực gió mạnh hoặc mái
đưa ra xa, cần khảo sát để gia cố thêm.
```

**Có cần xin phép khi làm mái hiên không?**
```
Với mái hiên nhỏ, không thay đổi kết cấu chính của nhà thường không cần xin
phép riêng, tuy nhiên còn tùy quy định địa phương và loại nhà (nhà phố mặt
tiền, khu quy hoạch...). Anh chị nên hỏi thêm chính quyền địa phương nếu
công trình lớn hoặc đưa ra sát ranh đất.
```

**Mái hiên có bảo hành không?**
```
Có, Pro-Metal bảo hành khung và thi công theo thỏa thuận khi báo giá. Vật tư
(tôn, polycarbonate, bạt...) bảo hành theo chính sách nhà sản xuất.
```

**Có nhận sửa, thay mái hiên cũ không?**
```
Có thể khảo sát sửa dột, thay tôn/polycarbonate hoặc gia cố khung cũ nếu kết
cấu còn sử dụng được. Thợ cần xem hiện trạng trước khi báo cách làm.
```

---

## 12) Khối: Form báo giá (`quote_form`)

**Tiêu đề khối**
```
Gửi vị trí hoặc kích thước, nhận tư vấn mái hiên
```

**Mô tả**
```
Anh chị để lại số điện thoại kèm vị trí lắp (trước nhà, sân thượng, ban
công...), diện tích ước lượng và khu vực thi công. Có ảnh hiện trạng thì gửi
thêm qua Zalo để chúng tôi tư vấn nhanh hơn.
```

**Ảnh minh hoạ**: ảnh mái hiên hoàn thiện, sáng, đẹp (dùng làm ảnh bên cạnh form).
