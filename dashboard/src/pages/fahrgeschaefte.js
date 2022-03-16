import Backdrop from "@mui/material/Backdrop";
import Box from "@mui/material/Box";
import CircularProgress from "@mui/material/CircularProgress";
import Container from "@mui/material/Container";
import Typography from "@mui/material/Typography";
import Head from "next/head";
import React, { useEffect, useState } from "react";
import fetchapi from "src/lib/fetchapi";
import Layout from "../components/dashboard-layout";
import DataTable from "../components/data/data-table";

const Fahrgeschäfte = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [initialData, setInitialData] = useState(null);
  const [columns, setColumns] = useState([]);

  const ENTITY = "Fahrgeschäft";
  const PRIMARY_KEY = "Id";
  const TITLE = "Fahrgeschäfte";

  const fetchData = async () => {
    try {
      setIsLoading(true);
      const result = await fetchapi(`${ENTITY}`, "GET");
      setIsLoading(false);
      setInitialData(result.data);
      setColumns(result.schema);
    } catch (e) {
      setIsLoading(false);
      console.error(e);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  const handleDelete = async (item) => {
    let result = false;
    try {
      setIsLoading(true);
      result = await fetchapi(`${ENTITY}/${item[PRIMARY_KEY]}`, "DELETE");
    } catch (e) {
      console.error(e);
    }
    setIsLoading(false);
    return result;
  };
  const handleCreate = async (item) => {
    try {
      setIsLoading(true);
      const result = await fetchapi(`${ENTITY}`, "POST", item);
      setIsLoading(false);
      return result.data;
    } catch (e) {
      setIsLoading(false);
      console.error(e);
      return null;
    }
  };
  const handleUpdate = async (prevItem, newItem) => {
    let result = false;
    try {
      setIsLoading(true);
      result = await fetchapi(`${ENTITY}/${prevItem[PRIMARY_KEY]}`, "PUT", newItem);
    } catch (e) {
      console.error(e);
    }
    setIsLoading(false);
    return result;
  };

  return (
    <>
      <Head>
        <title>Tierpark Thüle | {TITLE}</title>
      </Head>
      <Box
        component="main"
        sx={{
          flexGrow: 1,
          py: 8,
          px: 0,
        }}
      >
        {columns && initialData ? (
          <Container maxWidth={false}>
            <DataTable
              initialData={initialData}
              columns={columns}
              title={TITLE}
              primaryKey={PRIMARY_KEY}
              onDelete={handleDelete}
              onCreate={handleCreate}
              onUpdate={handleUpdate}
              isLoading={isLoading}
              setIsLoading={setIsLoading}
              sx={{ px: 0 }}
            />
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
Fahrgeschäfte.getLayout = (page) => <Layout>{page}</Layout>;

export default Fahrgeschäfte;
