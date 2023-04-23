import {Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Tooltip} from 'chart.js';
import { Line } from 'react-chartjs-2';
import { useI18n } from '@shopify/react-i18n';
import { Text, Box} from '@shopify/polaris';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip
);
  
export const ChartSale = () => {
  const [i18n] = useI18n();
  const options = {
      responsive: true,
      maintainAspectRatio: false
  };
  const labels = ['January', 'February', 'March', 'April', 'May','January', 'February', 'March', 'April', 'May', 'June', 'July'];
  const dataChart = [203,156,99,251,247,251,247];
    
  const data = {
    labels,
    datasets: [
      {
        data: labels.map((item, i) => dataChart[i]),
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
      },
    ],
  };
  return (
    <Box paddingBlockStart='6' paddingBlockEnd='6'>
      <Box background="bg" padding="8" shadow='md' borderRadius='2'>
        <Box paddingBlockEnd='6'>
          <Text variant="headingLg" as="h2" fontWeight='semibold'>
            {i18n.translate('SimiCart.Page.Dashboard.Chart.Title')}
          </Text>
        </Box>
        <div className='h-[375px]'>
          <Line options={options} data={data} />
        </div>
      </Box>
    </Box>
  )
}