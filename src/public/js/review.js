document.addEventListener('DOMContentLoaded', function () {
    const shopId = document.querySelector('input[name="shop_id"]').value;
    console.log('Shop ID:', shopId);

    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch(`/shops/${shopId}/reviews`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok) {
                    alert('レビューを投稿しました');
                    location.reload();
                } else {
                    alert(data.message || 'エラーが発生しました');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('エラーが発生しました');
            }
        });
    }

    document.querySelectorAll('.delete-review').forEach(button => {
        button.addEventListener('click', async function () {
            if (confirm('このレビューを削除してもよろしいですか？')) {
                console.log('Attempting to delete review for shop:', shopId);

                try {
                    const response = await fetch(`/shops/${shopId}/reviews`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });

                    if (response.ok) {
                        console.log('Review deleted successfully');
                        location.reload();
                    }
                } catch (error) {
                    console.error('Review deletion error:', error);
                    alert('削除中にエラーが発生しました');
                }
            }
        });
    });

    const textarea = document.querySelector('textarea[name="comment"]');
    const charCount = document.querySelector('.char-count');
    if (textarea && charCount) {
        textarea.addEventListener('input', function () {
            const length = this.value.length;
            charCount.textContent = `${length}/300`;
            console.log('Comment length updated:', length);
        });
    }
});
