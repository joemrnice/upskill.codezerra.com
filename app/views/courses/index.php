<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Course Catalog</h1>
        <p class="text-gray-600">Explore our comprehensive collection of training courses</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="courses.php" id="filterForm" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    id="searchInput"
                    value="<?php echo e($filters['search']); ?>" 
                    placeholder="Search courses by title, description, or instructor..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                <svg class="absolute right-3 top-3.5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo e($cat['category']); ?>" <?php echo $filters['category'] === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo e(ucfirst($cat['category'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty</label>
                    <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">All Levels</option>
                        <option value="beginner" <?php echo $filters['difficulty'] === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                        <option value="intermediate" <?php echo $filters['difficulty'] === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="advanced" <?php echo $filters['difficulty'] === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="popular" <?php echo $filters['sort'] === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="title" <?php echo $filters['sort'] === 'title' ? 'selected' : ''; ?>>Title (A-Z)</option>
                    </select>
                </div>

                <!-- View Toggle -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">View</label>
                    <div class="flex gap-2">
                        <button type="button" id="gridView" class="flex-1 px-4 py-2 border border-purple-600 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 3H3v7h7V3zm11 0h-7v7h7V3zM10 14H3v7h7v-7zm11 0h-7v7h7v-7z"/>
                            </svg>
                        </button>
                        <button type="button" id="listView" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gradient-primary text-white rounded-lg hover:opacity-90">
                Apply Filters
            </button>
        </form>
    </div>

    <!-- Course Grid -->
    <div id="courseContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($courses)): ?>
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No courses found</h3>
                <p class="text-gray-500">Try adjusting your filters or search terms</p>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Course Thumbnail -->
                    <div class="relative h-48 bg-gradient-primary overflow-hidden">
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?php echo e($course['thumbnail']); ?>" alt="<?php echo e($course['title']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Enrollment Badge -->
                        <?php if (in_array($course['id'], $enrolledCourseIds)): ?>
                            <div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Enrolled
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Course Info -->
                    <div class="p-5">
                        <!-- Category and Difficulty -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                <?php echo e(ucfirst($course['category'])); ?>
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                <?php echo e(ucfirst($course['difficulty_level'])); ?>
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                            <?php echo e($course['title']); ?>
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?php echo e($course['description']); ?>
                        </p>

                        <!-- Instructor -->
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <?php echo e($course['instructor_name']); ?>
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <?php echo $course['enrollment_count']; ?> students
                            </div>
                            <?php if ($course['duration']): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                    </svg>
                                    <?php echo e($course['duration']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Button -->
                        <a href="<?php echo base_url('course.php?id=' . $course['id']); ?>" class="block w-full text-center px-4 py-2 bg-gradient-primary text-white rounded-lg hover:opacity-90 transition">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex justify-center">
            <nav class="flex space-x-2">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($filters['search']); ?>&category=<?php echo urlencode($filters['category']); ?>&difficulty=<?php echo urlencode($filters['difficulty']); ?>&sort=<?php echo urlencode($filters['sort']); ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="px-4 py-2 bg-purple-600 text-white rounded-lg"><?php echo $i; ?></span>
                    <?php elseif ($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($filters['search']); ?>&category=<?php echo urlencode($filters['category']); ?>&difficulty=<?php echo urlencode($filters['difficulty']); ?>&sort=<?php echo urlencode($filters['sort']); ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                            <?php echo $i; ?>
                        </a>
                    <?php elseif (abs($i - $currentPage) == 3): ?>
                        <span class="px-4 py-2">...</span>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($filters['search']); ?>&category=<?php echo urlencode($filters['category']); ?>&difficulty=<?php echo urlencode($filters['difficulty']); ?>&sort=<?php echo urlencode($filters['sort']); ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        Next
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script>
// Real-time search with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Auto-submit on filter change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', () => {
        document.getElementById('filterForm').submit();
    });
});

// View toggle
const gridView = document.getElementById('gridView');
const listView = document.getElementById('listView');
const courseContainer = document.getElementById('courseContainer');

gridView.addEventListener('click', () => {
    courseContainer.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
    gridView.classList.add('bg-purple-600', 'text-white', 'border-purple-600');
    gridView.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
    listView.classList.remove('bg-purple-600', 'text-white', 'border-purple-600');
    listView.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
});

listView.addEventListener('click', () => {
    courseContainer.className = 'grid grid-cols-1 gap-6';
    listView.classList.add('bg-purple-600', 'text-white', 'border-purple-600');
    listView.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
    gridView.classList.remove('bg-purple-600', 'text-white', 'border-purple-600');
    gridView.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
    
    // Change card layout for list view
    document.querySelectorAll('.course-card').forEach(card => {
        card.classList.add('flex', 'flex-row');
        card.querySelector('.relative').classList.remove('h-48');
        card.querySelector('.relative').classList.add('w-64', 'h-auto');
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
