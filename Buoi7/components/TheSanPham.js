// Mỗi lần dùng <the-san-pham> sẽ tạo một component có data riêng.
const TheSanPham = {
    props: {
        ten: { type: String, required: true },
        gia: { type: Number, required: true },
        anh: { type: String, default: '' },
        moTa: { type: String, default: 'Sản phẩm chưa có mô tả.' },
        soLuongTon: { type: Number, default: 0 }
    },

    data() {
        return {
            hienMoTa: false,
            thongBaoMua: ''
        };
    },

    methods: {
        dinhDangTien(gia) {
            return gia.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        },
        muaSanPham() {
            if (this.soLuongTon <= 0) return;
            this.thongBaoMua = `Bạn đã chọn mua ${this.ten}.`;
        }
    },

    template: `
        <article class="product-card">
            <img v-if="anh" class="product-image" :src="anh" :alt="ten">
            <div v-else class="image-placeholder">Chưa có ảnh</div>
            <div class="product-body">
                <span class="badge" :class="soLuongTon > 0 ? 'in-stock' : 'out-stock'">
                    {{ soLuongTon > 0 ? 'Còn hàng' : 'Hết hàng' }}
                </span>
                <h2>{{ ten }}</h2>
                <p class="price">{{ dinhDangTien(gia) }}</p>
                <p class="stock">Số lượng tồn: {{ soLuongTon }}</p>
                <div class="actions">
                    <button type="button" class="description-button"
                        @click="hienMoTa = !hienMoTa" :aria-expanded="hienMoTa">
                        {{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}
                    </button>
                    <button type="button" class="buy-button" :disabled="soLuongTon <= 0"
                        @click="muaSanPham">Mua</button>
                </div>
                <p v-if="hienMoTa" class="description">{{ moTa }}</p>
                <p class="purchase-message" role="status">{{ thongBaoMua }}</p>
            </div>
        </article>
    `
};
