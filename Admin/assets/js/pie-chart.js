
let chartConfig = {
    globals: {
        fontFamily: 'Ubuntu'
    },
    layout: 'h',
    graphset: [{
        type: 'pie',
        backgroundColor: '#fff',

        legend: {
            margin: 'auto auto 0% auto',
            backgroundColor: 'none',
            borderWidth: '0px',
            item: {
                color: '%backgroundcolor'
            },
            layout: 'float',
            marker: {
                borderRadius: '3px',
                borderWidth: '0px'
            },
            shadow: false
        },
        plot: {
            tooltip: {
                text: '%v USERS',
                borderRadius: '3px',
                shadow: false
            },
            valueBox: {
                visible: false
            },
            marginRight: '50px',
            borderWidth: '0px',
            shadow: false,
            size: '100px',
            slice: 50
        },
        plotarea: {

            backgroundColor: '#FFFFFF',
            borderColor: '#fff',
            borderRadius: '3px',
            borderWidth: '1px',
            marginTop: '0px',
            marginBottom: '5px'

        },

        series: [{
            text: 'New User',
            values: [170],
            top: '45px',
            backgroundColor: '#6CCFDF'
        },
        {
            text: 'Contest Join',
            values: [230],
            backgroundColor: '#E76D45'
        },
        {
            text: 'Disable User',
            values: [148],
            backgroundColor: '#55BA72'
        }
        ]
    },

    ]
};

zingchart.render({
    id: 'myChart',
    data: chartConfig
});