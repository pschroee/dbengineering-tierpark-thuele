import AttractionsIcon from "@mui/icons-material/Attractions";
import EventIcon from "@mui/icons-material/Event";
import FenceIcon from "@mui/icons-material/Fence";
import FoodBankIcon from "@mui/icons-material/FoodBank";
import LocalConvenienceStoreIcon from "@mui/icons-material/LocalConvenienceStore";
import PersonIcon from "@mui/icons-material/Person";
import PetsIcon from "@mui/icons-material/Pets";
import TopicIcon from "@mui/icons-material/Topic";
import { Typography } from "@mui/material";
import Backdrop from "@mui/material/Backdrop";
import Box from "@mui/material/Box";
import CircularProgress from "@mui/material/CircularProgress";
import Container from "@mui/material/Container";
import Grid from "@mui/material/Grid";
import Head from "next/head";
import { useEffect, useState } from "react";
import fetchapi from "src/lib/fetchapi";
import Layout from "../components/dashboard-layout";
import DashboardItem from "../components/dashboard/dashboard-item";

const Dashboard = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [data, setData] = useState({});

  const fetchData = async () => {
    setIsLoading(true);
    const result = await fetchapi("Dashboard", "GET");
    console.log(result.data);
    setData(result.data);
    setIsLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, []);
  return (
    <>
      <Head>
        <title>Tierpark Thüle | Dashboard</title>
      </Head>
      <Box
        component="main"
        sx={{
          flexGrow: 1,
          py: 8,
        }}
      >
        {!isLoading ? (
          <Container maxWidth={false}>
            <Grid container spacing={3}>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Tiere"
                  value={`${data.Tier}`}
                  variant="error"
                  icon={<PetsIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Mitarbeiter"
                  value={`${data.Mitarbeiter}`}
                  variant="success"
                  icon={<PersonIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Gehege"
                  value={`${data.Gehege}`}
                  variant="primary"
                  icon={<FenceIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Fahrgeschäfte"
                  value={`${data.Fahrgeschäft}`}
                  variant="warning"
                  icon={<AttractionsIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Gastronomie"
                  value={`${data.Gastronomie}`}
                  variant="warning"
                  icon={<FoodBankIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Gebäude"
                  value={`${data.Gebäude}`}
                  variant="primary"
                  icon={<LocalConvenienceStoreIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Themenwelten"
                  value={`${data.Themenwelt}`}
                  variant="error"
                  icon={<TopicIcon />}
                />
              </Grid>
              <Grid item xl={3} lg={3} sm={6} xs={12}>
                <DashboardItem
                  title="Veranstaltungen"
                  value={`${data.Veranstaltung}`}
                  variant="success"
                  icon={<EventIcon />}
                />
              </Grid>
            </Grid>
          </Container>
        ) : (
          <Typography variant="h3" sx={{ px: 2 }}>
            Lade Daten...
          </Typography>
        )}
      </Box>
      <Backdrop sx={{ color: "#fff", zIndex: (theme) => theme.zIndex.modal + 1 }} open={isLoading}>
        <CircularProgress color="inherit" />
      </Backdrop>
    </>
  );
};

Dashboard.getLayout = (page) => <Layout>{page}</Layout>;

export default Dashboard;
