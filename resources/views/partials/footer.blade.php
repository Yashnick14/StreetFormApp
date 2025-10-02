<footer class="bg-black px-4 sm:px-6 md:px-12 pt-12 pb-6">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:justify-between gap-12 text-gray-400">
        
        <!-- Store Info -->
        <div class="md:w-1/4">
            <h2 class="text-xl font-semibold text-white">STREETFORM</h2>
            <p class="mt-4 text-sm leading-relaxed">
                STREETFORM is your go-to destination for premium fashion and accessories. 
                We focus on quality, modern style, and customer satisfaction to bring you 
                the best collections for Men and Women.
            </p>
        </div>

        <!-- Links Section -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            
            <!-- Categories -->
            <div>
                <h6 class="text-white font-medium mb-4">CATEGORIES</h6>
                <ul class="space-y-2">
                    <li><a href="{{ route('men.products') }}" class="hover:text-white">Men</a></li>
                    <li><a href="{{ route('women.products') }}" class="hover:text-white">Women</a></li>
                    <li><a href="{{ route('all.products') }}" class="hover:text-white">All</a></li>
                </ul>
            </div>

            <!-- Policies -->
            <div>
                <h6 class="text-white font-medium mb-4">POLICIES</h6>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white">User Agreement</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Contact (wider column) -->
            <div>
                <h6 class="text-white font-medium mb-4">CONTACT</h6>
                <div class="space-y-2 text-sm break-words">
                    <p>Email: Streetform@gmail.com</p>
                    <p>Mobile: 0771154569</p>
                    <p>Address: 10 Marine drive, Kandy</p>
                </div>
            </div>

            <!-- Social Media -->
            <div>
                <h6 class="text-white font-medium mb-4">FOLLOW US</h6>
                <ul class="flex space-x-4">
                    <li><a href="#"><i class="fab fa-facebook text-white text-xl"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-x-twitter text-white text-xl"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram text-white text-xl"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <hr class="my-6 border-gray-700" />

    <div class="max-w-6xl mx-auto text-center">
        <p class="text-gray-400 text-xs">
            © STREETFORM {{ date('Y') }}. All rights reserved.
        </p>
    </div>
</footer>
