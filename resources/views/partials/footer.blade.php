<footer class="bg-[#342821] text-[#faf5ee]">
    <div class="mx-auto grid max-w-7xl grid-cols-[1.35fr_1fr] gap-x-5 gap-y-8 px-5 py-9 md:grid-cols-[1.8fr_1fr_0.7fr] md:gap-x-10 lg:px-8 lg:py-11">
        <div class="col-span-2 flex items-start gap-4 md:col-span-1">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center bg-white text-[#ae9270]" aria-hidden="true">
                <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                </svg>
            </span>
            <div>
                <p class="font-storefront-heading text-2xl leading-snug sm:text-[1.75rem] sm:leading-snug">Tỏa sáng<br>theo cách của bạn</p>
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-[#e1d3c5]">Trang sức tinh tế cho những khoảnh khắc đáng nhớ.</p>
            </div>
        </div>

        <div>
            <h2 class="font-storefront-heading text-lg leading-snug">Hỗ trợ khách hàng</h2>
            <ul class="mt-3 grid gap-2 text-sm leading-relaxed text-[#e1d3c5]">
                <li>Liên hệ</li>
                <li>Hướng dẫn mua hàng</li>
                <li>Chính sách đổi trả</li>
            </ul>
        </div>

        <div>
            <h2 class="font-storefront-heading text-lg leading-snug">Kết nối</h2>
            <div class="mt-3 flex flex-wrap gap-2 text-[#e2cbb0]" role="group" aria-label="Mạng xã hội">
                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#796453]" role="img" aria-label="Facebook" title="Facebook">
                    <x-storefront-icon name="brand-facebook" class="h-[18px] w-[18px]" />
                </span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#796453]" role="img" aria-label="Instagram" title="Instagram">
                    <x-storefront-icon name="brand-instagram" class="h-[18px] w-[18px]" />
                </span>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="border-t border-[#6d5848] py-5 text-center text-xs leading-relaxed text-[#d8c9bb]">© {{ date('Y') }} Luna Jewelry. Đã đăng ký bản quyền.</p>
    </div>
</footer>
