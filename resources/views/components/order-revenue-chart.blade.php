{{-- resources/views/components/product-analysis-chart.blade.php --}}

<div class="chart-container">
    <div class="chart-header">
        <div>
            <h2 class="chart-title">Order Analysis</h2>
        </div>
        <div class="growth-badge">18%</div>
    </div>
    
    <div class="legend">
        <div class="legend-item">
            <div class="legend-dot orders"></div>
            <span>Orders</span>
        </div>
        <div class="legend-item">
            <div class="legend-dot revenue"></div>
            <span>Revenue</span>
        </div>
    </div>
    
    <div class="chart-canvas">
        <canvas id="productAnalysisChart"></canvas>
    </div>
</div>

<style>
.chart-container {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    max-width: 100%;
    margin: 0 auto;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.chart-subtitle {
    font-size: 12px;
    color: #6b7280;
    margin: 4px 0 0 0;
}

.growth-badge {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 4px 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
}

.legend {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
    justify-content: flex-end;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6b7280;
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.legend-dot.orders {
    background: #10b981;
}

.legend-dot.revenue {
    background: #3b82f6;
}

.chart-canvas {
    height: 300px;
    position: relative;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('productAnalysisChart').getContext('2d');
    
    // Weekly data for orders and revenue
    const ordersData = [25, 42, 38, 55, 48, 35, 28];  // Mon-Sun
    const revenueData = [180, 240, 220, 310, 285, 195, 165];  // Mon-Sun
    
    const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Orders',
                    data: ordersData,
                    borderColor: '#10b981',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        
                        if (!chartArea) return null;
                        
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.6)');
                        gradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.3)');
                        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    order: 2
                },
                {
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: '#3b82f6',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        
                        if (!chartArea) return null;
                        
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.6)');
                        gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.3)');
                        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderWidth: 0,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const datasetLabel = context.dataset.label;
                            const value = context.parsed.y;
                            if (datasetLabel === 'Revenue') {
                                return `${datasetLabel}: ${value}`;
                            } else {
                                return `${datasetLabel}: ${value}`;
                            }
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: true,
                        color: 'rgba(107, 114, 128, 0.1)'
                    },
                    ticks: {
                        display: true,
                        color: '#6b7280',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    },
                    title: {
                        display: true,
                       
                        color: '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    display: true,
                    grid: {
                        display: true,
                        color: 'rgba(107, 114, 128, 0.1)'
                    },
                    beginAtZero: true,
                    ticks: {
                        display: true,
                        color: '#6b7280',
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return value;
                        }
                    },
                    title: {
                        display: true,
                       
                        color: '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 2
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>
@endpush