import {Head} from '@inertiajs/react';

const MetaData = ({title,data}) => {
    console.log(data);
    return (
       <>
       <Head>
          <title head-key="title">{title}</title>
          <meta head-key="description" name='description' content={data} />
       </Head>
       </>
   );   
}
export default MetaData;