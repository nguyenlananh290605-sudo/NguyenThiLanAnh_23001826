const DongGioHang = {
    props: {
        id: { type: Number, required: true },
        ten: { type: String, required: true },
        donGia: { type: Number, required: true },
        soLuong: {
            type: Number,
            required: true,
            validator: value => Number.isInteger(value) && value >= 1 && value <= 99
        }
    },

    // Component chỉ gửi yêu cầu; component cha kiểm tra và cập nhật dữ liệu.
    emits: ['doi-so-luong', 'yeu-cau-xoa'],

    computed: {
        thanhTien() {
            return this.donGia * this.soLuong;
        }
    },

    methods: {
        dinhDangTien(soTien) {
            return soTien.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        },
        doiSoLuong(soLuongMoi) {
            if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) return;
            this.$emit('doi-so-luong', this.id, soLuongMoi);
        },
        yeuCauXoa() {
            this.$emit('yeu-cau-xoa', this.id);
        }
    },

    template: `
        <tr>
            <td>#{{ id }}</td>
            <th scope="row">{{ ten }}</th>
            <td>{{ dinhDangTien(donGia) }}</td>
            <td>
                <div class="quantity-controls" role="group" :aria-label="'Số lượng ' + ten">
                    <button type="button" class="quantity-button"
                        :disabled="soLuong <= 1" :aria-label="'Giảm số lượng ' + ten"
                        @click="doiSoLuong(soLuong - 1)">−</button>
                    <span class="quantity">{{ soLuong }}</span>
                    <button type="button" class="quantity-button"
                        :disabled="soLuong >= 99" :aria-label="'Tăng số lượng ' + ten"
                        @click="doiSoLuong(soLuong + 1)">+</button>
                </div>
            </td>
            <td class="line-total">{{ dinhDangTien(thanhTien) }}</td>
            <td>
                <button type="button" class="delete-button"
                    :aria-label="'Xóa ' + ten + ' khỏi giỏ hàng'" @click="yeuCauXoa">Xóa</button>
            </td>
        </tr>
    `
};
