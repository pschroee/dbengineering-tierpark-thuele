import AddIcon from "@mui/icons-material/Add";
import DeleteIcon from "@mui/icons-material/Delete";
import EditIcon from "@mui/icons-material/Edit";
import MuiAlert from "@mui/material/Alert";
import Box from "@mui/material/Box";
import Card from "@mui/material/Card";
import IconButton from "@mui/material/IconButton";
import Snackbar from "@mui/material/Snackbar";
import Stack from "@mui/material/Stack";
import Tooltip from "@mui/material/Tooltip";
import Typography from "@mui/material/Typography";
import { intervalToDuration } from "date-fns";
import compareDateAsc from "date-fns/compareAsc";
import dateFormat from "date-fns/format";
import formatDuration from "date-fns/formatDuration";
import { de } from "date-fns/locale";
import MUIDataTable, { TableToolbar } from "mui-datatables";
import React, { forwardRef, useState } from "react";
import PerfectScrollbar from "react-perfect-scrollbar";
import DataModal from "./data-modal";
import DataTableSearch from "./data-table-search";

const Alert = forwardRef(function Alert(props, ref) {
  return <MuiAlert elevation={6} ref={ref} variant="filled" {...props} />;
});

export const DataTable = ({
  initialData,
  columns,
  title,
  primaryKey,
  onDelete = () => {},
  onUpdate = () => {},
  onCreate = () => {},
  isLoading,
  setIsLoading,
  ...rest
}) => {
  const [data, setData] = useState(initialData);

  const [editModalData, setEditModalData] = useState({});
  const [editModalOpen, setEditModalOpen] = useState(false);

  const [createModalData, setCreateModalData] = useState({});
  const [createModalOpen, setCreateModalOpen] = useState(false);

  const [lastDataIndex, setLastDataIndex] = useState(null);

  const [snackbarOpen, setSnackbarOpen] = useState(false);

  const [errorMessage, setErrorMessage] = useState("");

  // Custom renderer for specific column types
  const columnsUpdated = columns.map((column) => {
    switch (column.type) {
      case "dropdown":
        return {
          ...column,
          options: {
            ...column.options,
            customBodyRender: (value, tableMeta, updateValue) => {
              const label = column.dropdownItems.find((item) => item.value == value).label;
              return label;
            },
          },
        };
      case "datetime":
        return {
          ...column,
          options: {
            ...column.options,
            customBodyRender: (value, tableMeta, updateValue) => {
              const label = dateFormat(new Date(value), "dd.MM.yyyy HH:mm") + " Uhr";
              return label;
            },
            sortCompare: (order) => {
              return (obj1, obj2) => {
                let date1 = new Date(obj1.data);
                let date2 = new Date(obj2.data);
                return compareDateAsc(date1, date2) * (order === "asc" ? 1 : -1);
              };
            },
          },
        };
      case "timestampdiff":
        return {
          ...column,
          options: {
            ...column.options,
            customBodyRender: (value, tableMeta, updateValue) => {
              const seconds = value;
              const duration = intervalToDuration({
                start: new Date(tableMeta.rowData[3]),
                end: new Date(tableMeta.rowData[4]),
              });
              const formatted = formatDuration(duration, { locale: de });
              return value >= 0 ? formatted : `- ${formatted}`;
            },
            sortCompare: (order) => {
              return (obj1, obj2) => {
                let val1 = parseInt(obj1.data, 10);
                let val2 = parseInt(obj2.data, 10);
                return (val1 - val2) * (order === "asc" ? 1 : -1);
              };
            },
          },
        };
      default:
        return { ...column };
    }
  });

  const handleCloseSnackbar = (event, reason) => {
    if (reason === "clickaway") {
      return;
    }

    setSnackbarOpen(false);
  };

  const handleClickDelete = async (dataIndex) => {
    // TODO: Delete Single Item fetch
    try {
      setIsLoading(true);
      const success = await onDelete(data[dataIndex]);
      setIsLoading(false);
      if (!success) throw Error("Löschung fehlgeschlagen");
      setData((prev) => {
        prev.splice(dataIndex, 1);
        return [...prev];
      });
    } catch (e) {
      setErrorMessage(e.message);
      setSnackbarOpen(true);
    }
  };

  const handleClickEdit = (dataIndex) => {
    setLastDataIndex(dataIndex);
    setEditModalData({ ...data[dataIndex] });
    setEditModalOpen(true);
  };

  const handleClickCreate = () => {
    setCreateModalOpen(true);
    setCreateModalData({});
  };

  const handleUpdateItem = async (values) => {
    let emptyFields = false;
    columns.forEach((column) => {
      if ((values[column.name] == "" || values[column.name] == null) && column.required)
        emptyFields = true;
    });
    if (emptyFields) {
      setErrorMessage("Alle Felder mit * müssen gefüllt sein");
      setSnackbarOpen(true);
    } else {
      // TODO: Edit Single Item fetch
      try {
        setIsLoading(true);
        const success = await onUpdate(data[lastDataIndex], values);
        setIsLoading(false);
        if (!success) throw Error("Update fehlgeschlagen");
        setData((prev) => {
          prev[lastDataIndex] = success.data;
          return [...prev];
        });
        setEditModalOpen(false);
      } catch (e) {
        setErrorMessage(e.message);
        setSnackbarOpen(true);
      }
    }
  };

  const handleCreateItem = async (values) => {
    let emptyFields = false;
    columns.forEach((column) => {
      if ((values[column.name] == "" || values[column.name] == null) && column.required) {
        if (column.name != primaryKey) emptyFields = true;
      }
    });
    if (emptyFields) {
      setErrorMessage("Alle Felder mit * müssen gefüllt sein");
      setSnackbarOpen(true);
    } else {
      try {
        setIsLoading(true);
        const data = await onCreate(values);
        setIsLoading(false);
        if (data === null) throw Error("Eintrag konnte nicht erstellt werden");
        // if (generatedPrimaryKeyValue === null) throw Error("Eintrag konnte nicht erstellt werden");
        setData((prev) => {
          return [
            {
              ...data,
            },
            ...prev,
          ];
          // return [{ ...values, [primaryKey]: generatedPrimaryKeyValue }, ...prev];
        });
        setCreateModalOpen(false);
      } catch (e) {
        setErrorMessage(e.message);
        setSnackbarOpen(true);
      }
    }
  };

  const actionColumn = {
    name: "actions",
    label: "Aktionen",
    empty: true,
    options: {
      filter: false,
      sort: false,
      customBodyRenderLite: (dataIndex, rowIndex) => (
        <Stack direction="row">
          <Tooltip title="Bearbeiten">
            <IconButton onClick={() => handleClickEdit(dataIndex)}>
              <EditIcon />
            </IconButton>
          </Tooltip>
          <Tooltip title="Löschen">
            <IconButton onClick={() => handleClickDelete(dataIndex)}>
              <DeleteIcon />
            </IconButton>
          </Tooltip>
        </Stack>
      ),
    },
  };

  const options = {
    selectableRows: "none",
    customToolbar: () => (
      <>
        <Tooltip title={"Eintrag hinzufügen"}>
          <IconButton onClick={handleClickCreate}>
            <AddIcon />
          </IconButton>
        </Tooltip>
      </>
    ),
    customSearchRender: (searchText, handleSearch, hideSearch, options) => {
      return (
        <DataTableSearch
          searchText={searchText}
          onSearch={handleSearch}
          onHide={hideSearch}
          options={options}
        />
      );
    },
    print: false,
    download: false,
    textLabels: {
      body: {
        noMatch: "Keine passenden Einträge gefunden",
        toolTip: "Sortieren",
        columnHeaderTooltip: (column) => `Sortieren nach ${column.label}`,
      },
      pagination: {
        next: "Nächste Seite",
        previous: "Vorherige Seite",
        rowsPerPage: "Zeilen pro Seite:",
        displayRows: "von",
      },
      toolbar: {
        search: "Suche",
        downloadCsv: "CSV Export",
        print: "Drucken",
        viewColumns: "Spaltern",
        filterTable: "Filter",
      },
      filter: {
        all: "Alle",
        title: "FILTER",
        reset: "RESET",
      },
      viewColumns: {
        title: "Zeige Spalten",
        titleAria: "Zeige/Verstecke Tabellen Spalten",
      },
      selectedRows: {
        text: "Zeile(n) ausgewählt",
        delete: "Löschen",
        deleteAria: "Ausgewählte Zeilen löschen",
      },
    },
  };

  const components = {
    TableToolbar: (props) => (
      <TableToolbar {...props} title={<Typography variant="h4">{props.title}</Typography>} />
    ),
  };

  return (
    <>
      <Card {...rest}>
        <PerfectScrollbar>
          <Box sx={{ maxWidth: "100%" }}>
            <MUIDataTable
              title={title}
              data={data}
              columns={[...columnsUpdated, actionColumn]}
              options={options}
              components={components}
            />
          </Box>
        </PerfectScrollbar>
      </Card>
      <DataModal
        onSave={handleUpdateItem}
        open={editModalOpen}
        setOpen={setEditModalOpen}
        initialData={editModalData}
        title={`${title} bearbeiten`}
        fields={columns}
        primaryKey={primaryKey}
      />
      <DataModal
        onSave={handleCreateItem}
        open={createModalOpen}
        setOpen={setCreateModalOpen}
        initialData={createModalData}
        title={`${title} hinzufügen`}
        fields={columns}
        primaryKey={primaryKey}
      />
      <Snackbar open={snackbarOpen} autoHideDuration={4000} onClose={handleCloseSnackbar}>
        <Alert severity="error" onClose={handleCloseSnackbar} sx={{ width: "100%" }}>
          {errorMessage}
        </Alert>
      </Snackbar>
    </>
  );
};

export default DataTable;
